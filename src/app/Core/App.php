<?php

declare(strict_types=1);

namespace App\Core;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\RouteNotFoundException;
use App\Services\CustomMailer;
use App\Services\PaddlePayment;
use Dotenv\Dotenv;
use Symfony\Component\Mailer\MailerInterface;

class App
{

    protected static DB $db;
    protected Config $config;

    public function __construct(
        protected Container $container,
        protected ?Router $router = null,
        protected array $request = []
    ) {
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run(): void
    {
        try {
            echo $this->router->resolve(
                $this->request['uri'],
                $this->request['method']
            );
        } catch (RouteNotFoundException $e) {
            echo $e->getMessage();
        }
    }

    public function boot(): static
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__) . '/..');
        $dotenv->load();

        $this->config = new Config($_ENV);

        static::$db = new DB($this->config->db ?? []);

        $this->container->set(
            PaymentGatewayInterface::class,
            PaddlePayment::class
        );

        $this->container->set(
            MailerInterface::class,
            fn() => new CustomMailer($this->config->mailer['dsn'])
        );

        return $this;
    }

}