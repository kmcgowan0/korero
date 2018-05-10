<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\ORM\Query;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $term = $this->request->query('term');

        $this->paginate = [
            'contain' => ['interests']
        ];

        $users = $this->Users;

        if (!empty($term)) {
            $query = $users->find()->matching('Interests', function ($q) use ($term) {
                return $q->where(['Interests.name LIKE' => '%' . $term . '%']);
            });
            $users = $this->paginate($query);
        } else {
            $users = $this->paginate($this->Users);
        }

        $this->set(compact('users', 'interests', 'query', 'some_users'));
    }

    public function search()
    {

        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);

        $this->paginate = [
            'contain' => ['interests']
        ];

        $term = $this->request->getQuery('term');

//updating the radius to search in
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user_data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $user_data);
            if ($this->Users->save($user)) {
                return $this->redirect(['action' => 'search?term=' . $term]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $ids = [-1];

        foreach ($user->interests as $interest) {
            $interest_id = $interest->id;
            array_push($ids, $interest_id);
        }

//        $related_users_interests = $this->Users->find()->matching('Interests', function ($q) use ($ids) {
//            return $q->where(['Interests.id IN' => $ids]);
//        });

        $users = $this->Users;

        if (!empty($term)) {
            $related_users_interests = $users->find()->matching('Interests', function ($q) use ($term) {
                return $q->where(['Interests.name LIKE' => '%' . $term . '%']);
            });
            $search_result = $this->Users->Interests->find()->where(['name LIKE' => '%' . $term . '%']);
//            $related_users_interests = $this->paginate($query);
        } else {
            $search_result = null;
            $related_users_interests = null;
        }

        //empty array of matching data
        $user_matching_data = array();
        $bunch_of_interests = array();

        //foreach users interest entry add the matching data to an array
        foreach ($related_users_interests as $related_users_interest) {
            $uid = $related_users_interest['id'];
            $other_interests = $users->find()->matching('Interests', function ($q) use ($ids, $uid) {
                return $q->where(['Interests.id IN' => $ids, 'Users.id =' => $uid]);
            });
            foreach ($other_interests as $other_interest) {
                array_push($bunch_of_interests, $other_interest);
            }

        }
        array_push($user_matching_data, $bunch_of_interests);

        //get distance component
        $this->loadComponent('Distance');

        //only get distinct users
        $distinct_users = $related_users_interests->group('Users.id')->order('location', 'ASC');

        //if there are distinct users work out how much space each gets
        if ($distinct_users->count()) {
            $number_of_users = $distinct_users->count();

            $space_allocated = 360 / $number_of_users;
        }

        //empty array for counting interests
        $interest_count = array();

//        for each interest set the this interest variable to 0
        foreach ($related_users_interests as $an_interest) {
            $this_interest = 0;
            //for each bit of matching data check if it relates to the current users interests
            //if it does add it onto the this interest var
            foreach ($user_matching_data as $a_data) {
//                var_dump($a_data);
                foreach ($a_data as $a_datum) {
                    if ($a_datum['_matchingData']['UsersInterests']->user_id == $an_interest['id']) {
                        $this_interest++;
                    }
                }

            }
            //create associative array for each users interest where user id => number of mutual interests
            $interest_count[$an_interest['id']] = $this_interest;
        }

        //sort the interests from most to least
        arsort($interest_count);

        //slice the array to get the top 6
        $top_interests = array_slice($interest_count, 0, 6, true);

        //set empty array for users in radius
        $users_in_radius = array();
        //for each user get the distance from the main user
        foreach ($distinct_users as $distinct_user) {
            $distance = $this->Distance->getDistance($user['location'], $distinct_user['location']);
            //if the user is within the radius and in the top users array add it to the users in radius var
            if ($distance <= $user['radius'] && array_key_exists($distinct_user['id'], $top_interests)) {
                array_push($users_in_radius, $distinct_user);
            }
        }

        //would like this working

//        $this->loadComponent('Message');
//        $message = $this->Message->sendMessages($this->Auth->user('id'));

        $this->loadModel('Messages');

//        sending messages from within message view
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $id;
            //$message_data['recipient'] = $id;
            $message_data['sent'] = date('Y-m-d h:i');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('Message sent'));
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }


        $this->set(compact('user', 'users', 'search_result', 'interest_count', 'related_users_interests', 'user_matching_data', 'message', 'users_in_radius', 'space_allocated', 'term'));
        $this->set('_serialize', ['user']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);

        $from = new Time($user->dob);
        $to = Time::now();
        $user_age = $from->diff($to)->y;

        $ids = [-1];

        foreach ($user->interests as $interest) {
            $id = $interest->id;
            array_push($ids, $id);
        }

        $this->loadComponent('Allowed');

        $authorised_user = $this->Auth->user();
        $allowed_user = $this->Allowed->checkAllowed($user, $authorised_user);

        $this->loadComponent('Distance');

        $distance = round($this->Distance->getDistance($authorised_user['location'], $user['location']));

        //allowed user should return either true or false
        //true if authuser and user have any matching interests
        //true if any of the user's interests are in authusers list of interests
        //false if not
        if ($authorised_user['id'] == $user['id']) {
            $my_profile = true;
        } else {
            $my_profile = false;
        }

        $user_interests_array = array();
        foreach ($user->interests as $an_interest) {
            array_push($user_interests_array, $an_interest->name);
        }

        $auth_interests_array = array();
        foreach ($authorised_user['interests'] as $an_interest) {
            array_push($auth_interests_array, $an_interest['name']);
        }

        $mutual_interest_array = array_intersect($user_interests_array, $auth_interests_array);


        $this->set(compact('user', 'allowed_user', 'my_profile', 'mutual_interest_array', 'user_age', 'distance'));
        $this->set('_serialize', ['user']);
    }

    public function compareDeepValue($val1, $val2)
    {
        return strcmp($val1['id'], $val2['id']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user_data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $user_data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }


        $interests = $this->Users->Interests->find('list', ['limit' => 200]);
        $this->set(compact('user', 'interests'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user_data = $this->request->getData();
            if ($user_data['upload']['name'] != '') {
                $file = $user_data['upload'];
                $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
                $arr_ext = ['jpg', 'png']; //set allowed extensions
                $setNewFileName = time() . "_" . rand(000000, 999999);
                //only process if the extension is valid
                if (in_array($ext, $arr_ext)) {
                    //do the actual uploading of the file. First arg is the tmp name, second arg is
                    //where we are putting it
                    move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/' . $setNewFileName . '.' . $ext);

                    //prepare the filename for database entry
                    $imageFileName = $setNewFileName . '.' . $ext;

                }
                $user_data['upload'] = $imageFileName;
            } elseif ($user_data['upload']['name'] == null) {
                $user_data['upload'] = '';
            } else {
                $user_data['upload'] = $user->getOriginal('upload');
            }
            $user = $this->Users->patchEntity($user, $user_data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }


        $users_interests = $this->Users->UsersInterests->find('all');
        $users_interests->distinct('interest_id');

        //get count of each interest
        $number_of_interests = [];
        foreach ($users_interests as $users_interest) {
            $query = $this->Users->UsersInterests->find('all');
            $query->where(['interest_id' => $users_interest['interest_id']]);
            $number = $query->count();
            $number_of_interests[$users_interest['interest_id']] = $number;
        }
        //sort by count
        arsort($number_of_interests);

        //take the first 4 from the array
        $largest = array_slice($number_of_interests, 0, 4, true);

        //get array of just interest ids for query
        $top_interest_array = [];
        foreach ($largest as $key => $value) {
            array_push($top_interest_array, $key);
        }
        $top_interests = $this->Users->Interests->find('list')->where(['id IN' => $top_interest_array]);
        $interests = $this->Users->Interests->find('list', ['limit' => 200]);

        $auth_user = $this->Auth->user();

        if ($auth_user['id'] == $user['id']) {
            $my_profile = true;
        } else {
            $my_profile = false;
        }

        $this->set(compact('user', 'interests', 'top_interests', 'my_profile', 'user_data'));
    }

    public function editInterests()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                if (isset($this->request->data['new-interest'])) {
                    return $this->redirect(['controller' => 'Interests', 'action' => 'add']);
                } else {
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
            $this->log($this);
        }

        $users_interests = $this->Users->UsersInterests->find('all');
        $users_interests->distinct('interest_id');

        //get count of each interest
        $number_of_interests = [];
        foreach ($users_interests as $users_interest) {
            $query = $this->Users->UsersInterests->find('all');
            $query->where(['interest_id' => $users_interest['interest_id']]);
            $number = $query->count();
            $number_of_interests[$users_interest['interest_id']] = $number;
        }
        //sort by count
        arsort($number_of_interests);

        //take the first 4 from the array
        $largest = array_slice($number_of_interests, 0, 4, true);

        //get array of just interest ids for query
        $top_interest_array = [];
        foreach ($largest as $key => $value) {
            array_push($top_interest_array, $key);
        }
        $top_interests = $this->Users->Interests->find('list')->where(['id IN' => $top_interest_array]);

        $interests = $this->Users->Interests->find('list', ['limit' => 200]);
        $this->set(compact('user', 'interests', 'top_interests'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function removeInterest($uid = null, $iid = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($uid);
        $interest = $this->Users->Interests->get($iid);
        $this->Users->Interests->unlink($user, [$interest]);
        return $this->redirect(['action' => 'edit-interests', $uid]);
    }


    public function login()
    {
        $redirect_url = '/users/connections';
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                //on first login redirect to edit interests
                if ($user['loggedin'] == 0) {
                    $this->Users->query()->update()
                        ->set(['loggedin' => 1])
                        ->where(['id' => $user['id']])
                        ->execute();
                    return $this->redirect(['action' => 'edit-interests']);
                } else {
                    return $this->redirect($redirect_url);
                }
            }
            $this->Flash->error('Your username or password is incorrect.');
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function passwordReset()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if (!empty($this->request->data)) {
            $user = $this->Users->patchEntity($user, [
                'old_password' => $this->request->data['old_password'],
                'password' => $this->request->data['password1'],
                'password1' => $this->request->data['password1'],
                'password2' => $this->request->data['password2']
            ],
                ['validate' => 'password']
            );
            if ($this->Users->save($user)) {
                $this->Flash->success('The password is successfully changed');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('There was an error during the save!');
            }
        }
        $this->set('user', $user);
    }

    public function connections()
    {

        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);

//updating the radius to search in
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user_data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $user_data);
            if ($this->Users->save($user)) {
                return $this->redirect(['action' => 'connections']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $ids = [-1];

        foreach ($user->interests as $interest) {
            $interest_id = $interest->id;
            array_push($ids, $interest_id);
        }

        $related_users_interests = $this->Users->find()->matching('Interests', function ($q) use ($ids) {
            return $q->where(['Interests.id IN' => $ids]);
        });


        //empty array of matching data
        $user_matching_data = array();

        //foreach users interest entry add the matching data to an array
        foreach ($related_users_interests as $related_users_interest) {
            array_push($user_matching_data, $related_users_interest->_matchingData);
        }

        //get distance component
        $this->loadComponent('Distance');

        //only get distinct users
        $distinct_users = $related_users_interests->group('Users.id')->order('location', 'ASC');

        //if there are distinct users work out how much space each gets
        if ($distinct_users->count()) {
            $number_of_users = $distinct_users->count();

            $space_allocated = 360 / $number_of_users;
        }

        //empty array for counting interests
        $interest_count = array();

//        for each interest set the this interest variable to 0
        foreach ($related_users_interests as $an_interest) {
            $this_interest = 0;
            //for each bit of matching data check if it relates to the current users interests
            //if it does add it onto the this interest var
            foreach ($user_matching_data as $a_data) {
                if ($a_data['UsersInterests']->user_id == $an_interest['id']) {
                    $this_interest++;
                }
            }
            //create associative array for each users interest where user id => number of mutual interests
            $interest_count[$an_interest['id']] = $this_interest;
        }

        //sort the interests from most to least
        arsort($interest_count);

        //slice the array to get the top 6
        $top_interests = array_slice($interest_count, 0, 6, true);

        //set empty array for users in radius
        $users_in_radius = array();
        //for each user get the distance from the main user
        foreach ($distinct_users as $distinct_user) {
            $distance = $this->Distance->getDistance($user['location'], $distinct_user['location']);
            //if the user is within the radius and in the top users array add it to the users in radius var
            if ($distance <= $user['radius'] && array_key_exists($distinct_user['id'], $top_interests)) {
                array_push($users_in_radius, $distinct_user);
            }
        }

        //would like this working

//        $this->loadComponent('Message');
//        $message = $this->Message->sendMessages($this->Auth->user('id'));

        $this->loadModel('Messages');

//        sending messages from within message view
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $id;
            //$message_data['recipient'] = $id;
            $message_data['sent'] = date('Y-m-d h:i');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('Message sent'));
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }


        $this->set(compact('user', 'user_matching_data', 'message', 'users_in_radius', 'space_allocated'));
        $this->set('_serialize', ['user']);
    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout', 'login']);
    }


}
