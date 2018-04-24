<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SocialProfiles Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SocialProfile get($primaryKey, $options = [])
 * @method \App\Model\Entity\SocialProfile newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SocialProfile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SocialProfile|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SocialProfile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SocialProfile[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SocialProfile findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SocialProfilesTable extends Table
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

        $this->setTable('social_profiles');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
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
            ->scalar('provider')
            ->maxLength('provider', 255)
            ->requirePresence('provider', 'create')
            ->notEmpty('provider');

        $validator
            ->scalar('identifier')
            ->maxLength('identifier', 255)
            ->requirePresence('identifier', 'create')
            ->notEmpty('identifier');

        $validator
            ->scalar('profile_url')
            ->maxLength('profile_url', 255)
            ->allowEmpty('profile_url');

        $validator
            ->scalar('website_url')
            ->maxLength('website_url', 255)
            ->allowEmpty('website_url');

        $validator
            ->scalar('photo_url')
            ->maxLength('photo_url', 255)
            ->allowEmpty('photo_url');

        $validator
            ->scalar('display_name')
            ->maxLength('display_name', 255)
            ->allowEmpty('display_name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmpty('description');

        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->allowEmpty('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->allowEmpty('last_name');

        $validator
            ->scalar('gender')
            ->maxLength('gender', 255)
            ->allowEmpty('gender');

        $validator
            ->scalar('language')
            ->maxLength('language', 255)
            ->allowEmpty('language');

        $validator
            ->scalar('age')
            ->maxLength('age', 255)
            ->allowEmpty('age');

        $validator
            ->scalar('birth_day')
            ->maxLength('birth_day', 255)
            ->allowEmpty('birth_day');

        $validator
            ->scalar('birth_month')
            ->maxLength('birth_month', 255)
            ->allowEmpty('birth_month');

        $validator
            ->scalar('birth_year')
            ->maxLength('birth_year', 255)
            ->allowEmpty('birth_year');

        $validator
            ->email('email')
            ->allowEmpty('email');

        $validator
            ->scalar('email_verified')
            ->maxLength('email_verified', 255)
            ->allowEmpty('email_verified');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 255)
            ->allowEmpty('phone');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmpty('address');

        $validator
            ->scalar('country')
            ->maxLength('country', 255)
            ->allowEmpty('country');

        $validator
            ->scalar('region')
            ->maxLength('region', 255)
            ->allowEmpty('region');

        $validator
            ->scalar('city')
            ->maxLength('city', 255)
            ->allowEmpty('city');

        $validator
            ->scalar('zip')
            ->maxLength('zip', 255)
            ->allowEmpty('zip');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
