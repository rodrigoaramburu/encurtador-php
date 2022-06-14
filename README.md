## Encurtador de Url

### Como rodar a aplicação

A aplicação roda através do docker com docker-compose em um servidor nginx

```shell
docker-composer up -d --build
```

Copiar o arquivo **.env.example** para **.env** .

Para a instalar as dependências do projeto rode 

```shell
docker exec -it php_encurtador composer install
```

E para rodar o **worker** para processar as estatisticas de visualizações.

```shell
docker exec -it php_encurtador php worker.php
```


### Rotas 

Ver arquivo oepnapi.yaml
