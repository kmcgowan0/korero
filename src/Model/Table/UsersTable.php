<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * Users Model
 *
 * @property \App\Model\Table\InterestsTable|\Cake\ORM\Association\BelongsToMany $Interests
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Interests', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'interest_id',
            'joinTable' => 'users_interests'
        ]);

        $this->hasMany('ADmad/HybridAuth.SocialProfiles');

        \Cake\Event\EventManager::instance()->on('HybridAuth.newUser', [$this, 'createUser']);
    }

    public function createUser(\Cake\Event\Event $event)
    {
        // Entity representing record in social_profiles table
        $profile = $event->data()['profile'];

        // Make sure here that all the required fields are actually present

        $user = $this->newEntity(['email' => $profile->email]);
        $user = $this->save($user);

        if (!$user) {
            throw new \RuntimeException('Unable to save new user');
        }

        return $user;
    }

    public function findAuth($query, array $options)
    {
        return $query
            ->contain(['Interests']);
    }

    public function ofAge($dob)
    {
        $dob_string = $dob['year'] . '/' . $dob['month'] . '/' . $dob['day'];
        $from = new Time($dob_string);
        $to = Time::now();
        $user_age = $from->diff($to)->y;

        if ($user_age >= 18) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 32)
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 255)
            ->requirePresence('firstname', 'create')
            ->notEmpty('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 255)
            ->requirePresence('lastname', 'create')
            ->notEmpty('lastname');

        $validator
            ->scalar('location')
            ->maxLength('location', 255)
            ->requirePresence('location', 'create')
            ->allowEmpty('location');

        $validator
            ->scalar('coded_location')
            ->maxLength('coded_location', 255)
            ->requirePresence('coded_location', 'create')
            ->notEmpty('coded_location');

        $validator
            ->date('dob')
            ->allowEmpty('dob');

        $validator->add('dob', 'custom', [
            'rule' => [$this, 'ofAge'],
            'message' => 'Sorry, you\'re too young.'
        ]);

        return $validator;
    }

    public function validationPassword(Validator $validator)
    {

        $validator
            ->add('old_password', 'custom', [
                'rule' => function ($value, $context) {
                    $user = $this->get($context['data']['id']);
                    if ($user) {
                        if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                            return true;
                        }
                    }
                    return false;
                },
                'message' => 'The old password does not match the current password!',
            ])
            ->notEmpty('old_password');

        $validator
            ->add('password1', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'The password have to be at least 6 characters!',
                ]
            ])
            ->add('password1', [
                'match' => [
                    'rule' => ['compareWith', 'password2'],
                    'message' => 'The passwords does not match!',
                ]
            ])
            ->notEmpty('password1');
        $validator
            ->add('password2', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'The password have to be at least 6 characters!',
                ]
            ])
            ->add('password2', [
                'match' => [
                    'rule' => ['compareWith', 'password1'],
                    'message' => 'The passwords does not match!',
                ]
            ])
            ->notEmpty('password2');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
