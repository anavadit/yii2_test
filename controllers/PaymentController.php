<?php
class Payment
{
    /**
    * payFromUserBalance - производит оплату существующего заказа с баланса пользователя
    * (если у него есть средства на внутреннем счете)
    *
    * При просмотре заказа, есть кнопка оплатить с внутреннего счета,
    * при нажатии на нее отправляется ajax запрос к данному методу (сам пользователь в личном кабинете нажимает эту кнопку),
    *
    * если вернулось true - пользователь видит что оплата успешная и получает сообщение в телеграм
    * если вернулось false - пользователь видит сообщение «Недостаточно средств на вашем счете»
    *
    * дополнительно:
    * User и Order - стандартные модели для работы с бд (внутрь них не надо проваливаться)
    * $request->get($key) - возвращает $_GET[$key]
    * NotificationSender - отправляет сообщения (в его реализацию тоже не проваливаемся)
    */
    public function payFromUserBalance(Request $request)
    {
        $userId = $request->get('user_id');
        $orderId = $request->get('order_id');
        $sum = (float)$request->get('sum');
        // Для оплаты заказа должен быть Post-запрос и с проверкой csrf отправляемой формы, т.е. все поля с моделью в форме,
        // нужна проверка if $request->isPost() 
        // преобразование (float) непонятно зачем, оно не нужно
        
        $user = User::getUserById($userId);
        $userBalance = $user->balance;
        $user->balance -= $sum;
        $order = Order::getOrderById($orderId);
        // надо, чтобы заказ можно было получить по relation юзера $order->user

        if($sum <= $userBalance && $sum === $order->sum) {
            $order->status = Order::STATUS_PAID;
            (new NotificationSender())->sendTelegramMessage($user->id, 'Заказ # ' . $order->id . ' успешно оплачен!');
            // об успешной оплате надо слать уведомление после успешного сохранения в базе а не до
        } else {
            return false;
        }

        $user->save();
        $order->save();
        // методы надо поместить в блок try catch  и желательно добавить транзакцию, и добавить вложенность - при успешном сохранении $user делать сохранение $order, иначе: $transaction->rollBack()

        return true;
    }

    // без проверки на работоспособность я бы переписала метод так:
    public function payFromUserBalance2(Request $request)
    {
        if (!$request->isPost) {
            throw new BadRequestHttpException('Неверный запрос.');
        }
        $post = $request->post();
        $order = Order::getOrderById($post['order_id']);
        if ($order) {
            if ($order->status === Order::STATUS_PAID) {
                throw new NotFoundHttpException('Заказ '.$order->id.' уже оплачен.');
            }
            if ($user = $order->user) {
                if ($user->id == $post['user_id']) {
                    if($post['sum'] <= $user->balance && $post['sum'] === $order->sum) {

                        try {
                            $transaction = $order->getDb()->beginTransaction();

                            $user->balance -= $post['sum'];
                            if (false !== $user->update()) {
                                $order->status = Order::STATUS_PAID;
                                if (false !== $order->update()) {
                                    $transaction->commit();
                                    (new NotificationSender())->sendTelegramMessage($user->id, 'Заказ # ' . $order->id . ' успешно оплачен!');
                                    return true;
                                }
                            }
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            throw new BadRequestHttpException($e->getMessage());
                        }
                        
                    } else {
                        return false;
                    }
                } else {
                    throw new NotFoundHttpException('У заказа '.$order->id.' - другой покупатель: '.$user->id);
                }
            } else{
                throw new NotFoundHttpException('У заказа '.$order->id.' отсутствует покупатель.');
            }
        } else {
            throw new NotFoundHttpException('Нет заказа с номером: '.$post['order_id']);
        }

    }
}

