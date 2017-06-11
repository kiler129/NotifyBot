# NotifyBot

One day I wanted to buy Apple AirPods, but they're not available anywhere :( But wait!
I'm a programmer - I can program a robot which will check the availability of the product
across local stores.... so I did being bored ;)

![preview](https://raw.githubusercontent.com/kiler129/NotifyBot/master/preview.png)

## What it can do?
Well, you can get a nice table with availability of desired products in nearby Apple
Stores. It can also notify you via e-mail (or SMS) after products become available "Today"
in any of the stores around you (or rather your zip code).

## How to install this?
Since this project is more a toy in my spare time it has no real installation procedure.
Just clone the repo/download the zip file and execute `composer install`.

If you don't have composer take a look at https://getcomposer.org.

## How to configure this?
Project contains `config.sample.yml` file, rename it to `config.yml` and edit for your
needs as directed.

## How to use this?
This tool has two commands so far:
  - `notify:availability` which shows you availability summary once
  - `notify:run` which runs constant checks and messages you if needed

## Does it work on Windows?
No.
