<?php

namespace App\Controller\Api;

use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;
use function SebastianBergmann\Type\TestFixture\floatReturnType;

class OrderController extends AbstractController
{
    public function calculate_price(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request) : JsonResponse {
        $credentials = $request->request->all();

        // проверим валидность налогового кода
        $tax = $this->validate_country($credentials['taxNumber'], $validator, $entityManager);

        if ($tax instanceof JsonResponse) {
            return $tax;
        }

        $product = $entityManager->getRepository(Product::class)->find($credentials['product'])->toArray();

        $couponAmount = 0;

        // если применён купон
        if (!empty($credentials['couponCode'])) {
            $coupon = $entityManager->getRepository(Coupon::class)->findBy(['code' => $credentials['couponCode']]);

            if (empty($coupon)) {
                return $this->json(["message" => "Неверно введён код купона"], 400);
            }

            $coupon = $coupon[0]->toArray();

            switch ($coupon['type']) {
                case "amount":
                    $couponAmount = $coupon['value'];
                    break;
                case "percent":
                    $couponAmount = $product['price'] * (float)($coupon['value'] / 100);
            }
        }

        $taxAmount = 0;

        // считаем сумму налога от покупки
        if (!empty($tax)) {
            $taxAmount = $product['price'] * ($tax / 100);
        }

        $total = $product['price'] - $couponAmount + $taxAmount;

        return $this->json($total, 200);
    }

    public function purchase(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request) : JsonResponse {
        $total = json_decode($this->calculate_price($validator, $entityManager, $request));

        $credentials = $request->request->all();

        if ($this->handle_payment_processor($credentials['paymentProcessor'], json_decode($total))) {
            $purchase = new Purchase();
            $purchase->setCreatedAt(new \DateTime());
            $purchase->setTaxCode($credentials['taxNumber']);

            $entityManager->persist($purchase);
            $entityManager->flush();

            return $this->json([
                'message' => 'Платёж успешно завершён. Номер платежа: ' . $purchase->getId(),
            ], 200);
        }

        return $this->json(['message' => 'Платёж не совершён. Повторите попытку позже'], 500);
    }

    protected function validate_country(string $taxNumber, ValidatorInterface $validator, EntityManagerInterface $entityManager) {
        $violations = $validator->validate(
            $taxNumber,
            new \App\Validator\Constraints\TaxNumberConstraint()
        );

        // Если есть ошибки валидации
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $country = $entityManager->getRepository(Country::class)->findBy(['tax_code' => substr($taxNumber, 0, 2)]);

        if (empty($country)) {
            return $this->json(['message' => 'Страна не найдена'], 404);
        }

        $country = $country[0]->toArray();

        $taxAmount = $country['tax_amount'];

        return $taxAmount;
    }

    /**
     * @throws \Exception
     */
    protected function handle_payment_processor(string $paymentProcessor, $price): ?bool
    {
        switch ($paymentProcessor) {
            case "paypal":
                $processor = new PaypalPaymentProcessor();
                return $processor->pay(intval($price));
                break;
            case "stripe":
                $processor = new StripePaymentProcessor();
                return $processor->processPayment(floatval($price));
                break;
            default:
                return $this->json(['message' => "Неизвестная платежная система"], 400);
        }
    }
}
