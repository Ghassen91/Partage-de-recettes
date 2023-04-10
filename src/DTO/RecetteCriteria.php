<?php

namespace App\DTO;

class RecetteCriteria
{
    public ?string $titre = null;

    public ?string $user = null;

    public int $page = 1;

    public int $limit = 25;

    public string $orderBy = 'createdAt';

    public string $direction = 'DESC';
}