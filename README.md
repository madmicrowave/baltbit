baltbit.com
-

##### Task:

1) Разработать скрипт для получения и анализа транзакций в блокчейн сети Ethereum за
последние 10 минут. Необходимо выделить транзакции переводов ETH, оценочная
стоимость которых соответствует заданной величине в евро (+-3%) по курсу биржи
binance.com ( Binance - Cryptocurrency Exchange for Bitcoin, Ethereum &amp; Altcoins ). Также
требуется создание простого веб-интерфейса для визуализации результатов и написание
unit-тестов для основных функциональностей.
2) Разрешается использовать любые доступные API, парсинг, прокси и в целом нет
ограничений.
3) Загрузить результаты работы в репозиторий (например, на GitHub). Написать краткий
README.md. (Today I Learned for programmers - Tiloid)

-------------------   
* Для выполнения задачи не требуются уверенное понимание криптовалют, достаточно
  базовых понятий, которые можно получить довольно быстро из google.

### HOW TO SETUP?

##### Requirements

* [Docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/) >= 24.0.x
* [Docker-Compose](https://docs.docker.com/compose/install/) >= 2.24.x

##### Install & Run

* Clone repository
    * [https://github.com/madmicrowave/baltbit.git](https://github.com/madmicrowave/baltbit)
* Run command to setup infrastructure: `make start`
* Access point
  * Web: http://127.0.0.1:9001/

##### Additional settings


**Coded by [Maksims Gerasimovs](https://github.com/madmicrowave)**\
**Powered by [baltbit.com](https://baltbit.com/)** 
