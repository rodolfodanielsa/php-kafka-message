<?php

namespace App\Components;

trait NameRandomizer
{
    public function returnFirstMessage(): string
    {
        return "Hi, ";
    }
    public function returnRandomName(string $payload): string
    {
        return $payload . array_rand(
            array_flip([
                'Joao', 'Bram', 'Gabriel', 'Fehim', 'Eni', 'Patrick', 'Micha', 'Mirzet', 'Liliana', 'Sebastien'
            ]),
            1
        ) . ".";
    }

    public function returnLastMessage(string $payload): string
    {
        return $payload . " Bye.";
    }
}