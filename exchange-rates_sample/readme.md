На сайте Центробанка РФ есть веб-сервис для получения курса валют на заданную дату: http://www.cbr.ru/scripts/Root.asp?Prtid=DWS

Необходимо написать скрипт, который позволит выбрать валюту из списка доступных на сайте ЦБ, выбрать год, месяц и получить в табличном виде курс данной валюты + увидеть график изменения курса.

Для построения графика использовать библиотеку http://www.amcharts.com/ или аналогичную.

Необходимо предусмотреть кеширование для увеличения скорости работы и уменьшения количества запросов к источнику данных на стороне ЦБ.

Система должна быть пригодна для максимально быстрого деплоя на LAMP сервер в любое место DocumentRoot.