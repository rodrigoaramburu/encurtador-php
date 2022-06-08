<?php 

declare(strict_types=1);

use Slim\Psr7\Uri;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use PHPUnit\Framework\Assert;
use Slim\Psr7\Factory\StreamFactory;


trait PDOHelper
{

    private PDO $conn;

    protected function initPDO(): self
    {
        $this->conn = new PDO('sqlite::memory:');
        return $this;
    }

    protected function pdoConn(): PDO
    {
        return $this->conn;
    }
    
    protected  function loadCreateTable(string $path): self
    {
        $sql = file_get_contents($path);
        $this->conn->exec($sql);

        return $this;
    }
    
    protected function assertDatabaseHas(string $table, array $data): void
    {
        $where = join( ' AND ', array_map( function($key){
            return "$key = :$key";
        }, array_keys($data) ));

        $query = $this->conn->prepare("SELECT * FROM {$table} WHERE {$where}");
        $query->execute($data);
        $dbData = $query->fetch();
        
        foreach($data as $key => $value) {
            Assert::assertEquals($value, $dbData[$key] ?? null , "'{$value}' não foi encontrado na tabela {$table}");
        }
    }
}


function createRequest(
    string $method,
    string $path,
    array $headers = ['HTTP_ACCEPT' => 'application/json'],
    array $serverParams = [],
    string $body = '',
): Request {
    $uri = new Uri('', '', 80, $path);
   
    $handle = fopen('data://text/plain;base64,' . base64_encode($body),'r');
    $stream = (new StreamFactory())->createStreamFromResource($handle);

    $h = new Headers();
    foreach ($headers as $name => $value) {
        $h->addHeader($name, $value);
    }

    return new Request($method, $uri,  $h , [], $serverParams, $stream);
}


function dd(mixed $data): void
{
    var_dump($data);
    die();
}