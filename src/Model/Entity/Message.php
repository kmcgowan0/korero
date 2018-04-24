<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property string $body
 * @property \Cake\I18n\FrozenTime $sent
 *
 * @property \App\Model\Entity\Sender $sender
 * @property \App\Model\Entity\Recipient $recipient
 */
class Message extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'sender' => true,
        'recipient' => true,
        'body' => true,
        'sent' => true
    ];
}
