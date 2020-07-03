<h1>Invoice Payment Module</h1>

<h3>Установка</h3>

1. Скачайте [плагин]() и распакуйте его в корневую директорию вашего сайта
2. Выполните команду в консоли:
```shell script
php artisan plugin:refresh invoice.payment
```
2. В админ-панели перейдите во вкладку **Settings->Invoice->Настройки модуля**, затем введите свои данные и сохраните настройки
3. Перейдите по вкладку **Settings->Payment methods**, затем нажмите "Create"
4. Введите название "Invoice" и код "invoice", в поле "Payment gateway" выберете "Invoice"
5. Добавьте уведомление в личном кабинете Invoice(Вкладка Настройки->Уведомления->Добавить)
   с типом **WebHook** и адресом: **%URL сайта%/callback**<br>
   ![Imgur](https://imgur.com/lMmKhj1.png)