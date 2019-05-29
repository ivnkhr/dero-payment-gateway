# dero-payment-gateway
Dero Payment Gateway a.k.a DeroPay

This project consists of 3 major parts:
1. Payment Gateway Backend (issuing invoices, verifying payments)
2. User Interface (paying invoice, tracking down payment)
3. Example, wraps Payment Gateway api and uses its User Interface

check it live at: http://deropay.plrs.pro/

`Payment Gateway Backend and User Interface`

Are both bundled together and using Laravel 5.8 php framework for its core.
Basic instalation, requirments and trouble shooting info is availiable at:
https://laravel.com/docs/5.8/installation

`Project Setup:`

1. Server Requirments is:
	PHP >= 7.1.3
	MySQL (or any laravel compatible DB)
	BCMath PHP Extension
	Ctype PHP Extension
	JSON PHP Extension
	Mbstring PHP Extension
	OpenSSL PHP Extension
	PDO PHP Extension
	Tokenizer PHP Extension
	XML PHP Extension

2. Pull project from git or unzip it

3. Perform Configuration and Web Server Configuration instructions from here: https://laravel.com/docs/5.8/installation#web-server-configuration

4. In project directory run commands `php artisan app:apisecret`
This will create your API key for issuing invoices and accessing its data

5. Perform Starting The Scheduler instructions from here: https://laravel.com/docs/5.8/scheduling#introduction
It will automate hot wallet withdrawals and invoice checking

6. Rename .env.example => .env and modify values accordingly

7. Cache your config (https://laravel.com/docs/5.8/configuration#configuration-caching) by running: `php artisan config:cache`

8. Install and run your local dero hot wallet: 
start dero-wallet-cli-windows-386.exe --wallet-file test1.wallet --rpc-server --rpc-bind=127.0.0.1:30307 --daemon-address=https://derowallet.io:443
be sure to include --rpc-server, so DeroPay will be able to reach your wallet

9. Enjoy !

`Configuring your DeroPay (.env):`

[Laravel Config]
APP_ENV=local // local - for local env and prod - for production server
APP_DEBUG=true // set false if your runing it on production, true will provide additional data in logs and display
APP_URL=http://localhost // enter entrypoint url that is accessible from web for your deropay instance

[Required mySQL (or any laravel compatible DB) configuration]
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

[DeroPay Config]
DEROPAY_API_SECRET=<should be generated via `php artisan app:apisecret`>
DEROPAY_API_ALLOW_INVOICE=true // This will enable Invoice Api endpoint
DEROPAY_API_ALLOW_DATA=false // This will enable Invoice Api endpoint
DEROPAY_API_ALLOW_WALLET=false // This will enable Invoice Api endpoint
DEROPAY_API_ALLOWED_IP=* // Comaseparated list of ips from where DeroPay api is accessible, * for any IP

DEROPAY_WALLET_COLD_STORAGE_ADDRESS= // Your cold storage Dero Address, in DeroPay funds are stored on servers hot wallet and withdrawn to cold storage by scheduler or manualy via api
DEROPAY_WALLET_WITHDRAW_THRESHOLD=10000000000000 // in this example DEROPAY_WALLET_WITHDRAW_THRESHOLD is set to 10 DERO, thus if hotwallet balance is less then 10 dero it will not be withdrawn (implemented to save on fees)
DEROPAY_WALLET_WITHDRAW_FEE=100000000000 // currently set to 0.1 it will not perform withdrawal if fee is higher that current one, but if less it will send tx
DEROPAY_WALLET_REQUIRED_CONFIRMATIONS=10 // you can set to 0 for instant confirmation, but its recomended to set it for atleast 20 on-chain confirmations

DEROPAY_PAYMENT_SUCCESS_REDIRECT_URL= //this will add button to success page with provided url
DEROPAY_PAYMENT_SUCCESS_WEBHOOK_ENDPOINT_URL=http://127.0.0.1:8000/api/public/webhook // DeroPay will send POST reqeust to this endpoint with invocie status updates (0 - created, 1 - outdated, 2 - success)

DEROPAY_WALLET_ENDPOINT=http://127.0.0.1:30307/ //Your local hot wallet rpc api url
DEROPAY_DAEMON_ENDPOINT=http://127.0.0.1:30306/ //Daemon url that is used for wallet

DEROPAY_LIVE_SUPPORT_URL= //this will add button to footer on each page with provided url

`Api Endpoints:`

Webhook will recieve POST data in folowing format (this is from exaple project):
(
    [id] => 116
    [name] => TIP
    [desc] => 25 DERO
    [price] => 25000000000000
    [ttl] => 60
    [created_at] => 2019-05-29 17:48:41
    [updated_at] => 2019-05-29 17:49:32
    [status] => 3
    [webhook_data] => Array
        (
            [customer_nick] => 
        )

    [payment_id] => 26730eb5aa6571d0a95014e6cee95892f15363eb0354936a88510a7079d4115e
    [integrated_address] => dETiZn9qdZqfqpaWa1mEUkZzeFzux5RnVdEgxtcHvgaWZ2cLJw6Vdxch2MwDkmhksS2RYWwEpXcCLd3SypaobAdgVkvN17Amt8yHy96cVyqb2Dg2kQhiXfiFcF9VWrejXKbE937XBN3bWb
    [remaining_ttl] => 8
    [txs] => Array
        (
            [0] => Array
                (
                    [id] => 31
                    [created_at] => 2019-05-29 17:49:32
                    [updated_at] => 2019-05-29 17:49:32
                    [tx] => a64480b23db67627f69d548baca97267ed99e24e40f93cea00637ddb5c1e6205
                    [height] => 688747
                    [wallet_height] => 688747
                    [invoice_id] => 116
                    [amount] => 25000000000000
                )

        )

    [invoice_signature] => 02101949c3d23a0298436af61e0e22f26c396b7462357be36347356afaa90f27
)
  
  
`Running example:`
Create supporters.txt file with write permissions
rename config.php.example => config.php and put your secret and deropay api link