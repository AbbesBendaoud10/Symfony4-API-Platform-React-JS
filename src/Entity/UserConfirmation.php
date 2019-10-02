<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *              "path"= "/users/confirm"
 *          }
 *      },
 *      itemOperations={}
 * )
 */
class UserConfirmation{

    public $confirmationToken;

}