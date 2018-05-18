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
    public function index($sort = null)
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
        $this->loadComponent('Mutual');
        $this->loadComponent('Age');

        //only get distinct users
        $distinct_users = $related_users_interests->group('Users.id');

        $distinct_user_array = array();
        foreach ($distinct_users as $distinct_user) {
            array_push($distinct_user_array, $distinct_user);

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
        $number_of_interests = $interest_count;

        $distance_sort = array();
        $interest_sort = array();
        foreach ($distinct_user_array as $key => $single_user) {
            //add age and distance to array
            $age = $this->Age->getAge($single_user->dob);
            $single_user['age'] = $age;
            $distance = $this->Distance->getDistance($user['location'], $single_user['location']);
            $single_user['distance'] = $distance;
            //create assoc array to sort by distance
            $distance_sort[$key] = $distance;

            foreach ($interest_count as $uid => $count) {
                if ($single_user['id'] == $uid) {
                    $single_user['interest_count'] = $count;
                }
            }
            $interest_sort[$key] = $single_user['interest_count'];

        }


        //sort the interests from most to least
        arsort($interest_count);

        //slice the array to get the top 15
        $top_interests = array_slice($interest_count, 0, 10, true);


        if ($sort == 'distance') {
            array_multisort($distance_sort, SORT_ASC, $distinct_user_array);
        } else if ($sort == 'interests') {
            array_multisort($interest_sort, SORT_DESC, $distinct_user_array);
        }

        $distinct_users = $this->paginate($distinct_users);

        $this->set(compact('user', 'user_matching_data', 'sort', 'message', 'distinct_users', 'users_in_radius', 'space_allocated', 'number_of_interests', 'interest_count', 'distinct_user_array', 'data'));
        $this->set('_serialize', ['user']);
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
            if ($user_data['radius'] > 1000000) {
                $user_data['radius'] = 1000000;
            }
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

        //empty array for counting interests
        $interest_count = array();

//        for each interest set the this interest variable to 0
        foreach ($related_users_interests as $an_interest) {
            $this_interest = 0;
            //for each bit of matching data check if it relates to the current users interests
            //if it does add it onto the this interest var
            foreach ($user_matching_data as $a_data) {
                foreach ($a_data as $a_datum) {
                    if ($a_datum['_matchingData']['UsersInterests']->user_id == $an_interest['id']) {
                        $this_interest++;
                    }
                }

            }
            //create associative array for each users interest where user id => number of mutual interests
            $interest_count[$an_interest['id']] = $this_interest;
        }

        $number_of_interests = $interest_count;

        //sort the interests from most to least
        arsort($interest_count);

        //slice the array to get the top 6
        $top_interests = array_slice($interest_count, 0, 10, true);

        //set empty array for users in radius
        $users_in_radius = array();
        $this->loadComponent('Blocked');
        $this->loadComponent('Age');

        //for each user get the distance from the main user
        foreach ($distinct_users as $distinct_user) {
            $distance = $this->Distance->getDistance($user['location'], $distinct_user['location']);
            $blocked_user = $this->Blocked->blockedUser($distinct_user, $user);
            $blocked_by = $this->Blocked->blockedBy($distinct_user, $user);
            //if the user is within the radius and in the top users array add it to the users in radius var
            if ($distance <= $user['radius'] && array_key_exists($distinct_user['id'], $top_interests) && $blocked_user == false && $blocked_by == false) {
                array_push($users_in_radius, $distinct_user);
            }
        }


        foreach ($users_in_radius as $users_in_radiu) {
            $distance = $this->Distance->getDistance($user['location'], $users_in_radiu['location']);
            $age = $this->Age->getAge($users_in_radiu['dob']);
            $users_in_radiu['distance'] = $distance;
            $users_in_radiu['age'] = $age;
        }

        if (count($users_in_radius)) {
            $number_of_users = count($users_in_radius);

            $space_allocated = 360 / $number_of_users;
        }


        $this->set(compact('user', 'users', 'search_result', 'interest_count', 'related_users_interests', 'distinct_users', 'user_matching_data', 'message', 'number_of_interests', 'users_in_radius', 'space_allocated', 'term', 'distance'));
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

        $this->loadComponent('Mutual');

        $mutual_interest_array = $this->Mutual->getMutual($user->interests, $authorised_user['interests']);

        //check if users have blocked each other
        $this->loadComponent('Blocked');

        $current_user = $this->Users->get($authorised_user['id']);

        $blocked_user = $this->Blocked->blockedUser($user, $current_user);
        $blocked_by = $this->Blocked->blockedBy($user, $current_user);


        $this->set(compact('user', 'blocked_user', 'blocked_by', 'allowed_user', 'my_profile', 'mutual_interest_array', 'user_age', 'distance'));
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
            $user_data['firstname'] = ucwords($user_data['firstname']);
            $user_data['lastname'] = ucwords($user_data['lastname']);
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
            $user_data['firstname'] = ucwords($user_data['firstname']);
            $user_data['lastname'] = ucwords($user_data['lastname']);
            $user = $this->Users->patchEntity($user, $user_data);

            if ($this->Users->save($user)) {
                $this->Auth->setUser($user);

                return $this->redirect(['action' => 'view', $user->id]);
            }
        }

        $interests = $this->Users->Interests->find('list', ['limit' => 200]);

        $auth_user = $this->Auth->user();

        if ($auth_user['id'] == $user['id']) {
            $my_profile = true;
        } else {
            $my_profile = false;
        }

        $this->set(compact('user', 'interests', 'my_profile', 'user_data'));
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
                $this->Auth->setUser($user);
                if (isset($this->request->data['new-interest'])) {
                    return $this->redirect(['controller' => 'Interests', 'action' => 'add']);
                } else {
                    return $this->redirect(['action' => 'view', $id]);
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

        $this_user_interests = $user->interests;

        $user_interest_ids = array();
        foreach ($this_user_interests as $this_user_interest) {
//            array_push($user_interest_ids, $this_user_interest['id']);
            $user_interest_ids[$this_user_interest['id']] = 1;
        }

        $diff_interests = array_diff_key($number_of_interests, $user_interest_ids);

        //take the first 4 from the array
        $largest = array_slice($diff_interests, 0, 20, true);

        //get array of just interest ids for query
        $top_interest_array = [];
        foreach ($largest as $key => $value) {
            array_push($top_interest_array, $key);
        }

        $top_interests = $this->Users->Interests->find('list')->where(['id IN' => $top_interest_array]);

        $interests = $this->Users->Interests->find('list', ['limit' => 200]);
        $interest = $this->Users->Interests->newEntity();
        $this->set(compact('user', 'interests', 'interest', 'top_interests', 'largest', 'users_interests', 'this_user_interests', 'number_of_interests', 'user_interest_ids', 'diff'));
    }

    public function refreshInterests()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);

        $this->set(compact('user'));
    }

    public function editProfilePicture()
    {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user_data = $this->request->getData();
            var_dump($user_data);
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
            } elseif ($user_data['remove-profile'] == 'yes') {
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

        $interests = $this->Users->Interests->find('list', ['limit' => 200]);

        $auth_user = $this->Auth->user();

        if ($auth_user['id'] == $user['id']) {
            $my_profile = true;
        } else {
            $my_profile = false;
        }

        $this->set(compact('user', 'interests', 'my_profile', 'user_data'));
    }

    public function removeProfilePicture()
    {
        $id = $this->Auth->user('id');

        $this->Users->query()->update()
            ->set(['upload' => ''])
            ->where(['id' => $id])
            ->execute();

        return $this->redirect(['action' => 'view', $id]);
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

    public function blockUser($uid = null)
    {
        $authorised_user = $this->Auth->user('id');
        $user = $this->Users->get($authorised_user);
        $blocked_array = array();
        $blocked_users = $user['blocked_users'];
        var_dump($blocked_users);
        $blocked_array = explode(",", $blocked_users);

        if (!in_array($uid, $blocked_array)) {
            array_push($blocked_array, $uid);
        }

        $new_blocked_list = implode(",", $blocked_array);
        $this->Users->query()->update()
            ->set(['blocked_users' => $new_blocked_list])
            ->where(['id' => $user['id']])
            ->execute();
        return $this->redirect(['action' => 'view', $uid]);


    }

    public function unblockUser($uid = null)
    {
        $authorised_user = $this->Auth->user('id');
        $user = $this->Users->get($authorised_user);
        $blocked_array = array();
        $blocked_users = $user['blocked_users'];
        var_dump($blocked_users);
        $blocked_array = explode(",", $blocked_users);

        if (($key = array_search($uid, $blocked_array)) !== false) {
            unset($blocked_array[$key]);
        }

        $new_blocked_list = implode(",", $blocked_array);
        $this->Users->query()->update()
            ->set(['blocked_users' => $new_blocked_list])
            ->where(['id' => $user['id']])
            ->execute();
        return $this->redirect(['action' => 'view', $uid]);


    }


    public function login()
    {
        $redirect_url = '/users/connections';

        if ($this->Auth->user()) {
            return $this->redirect($redirect_url);
        }
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

    public function sendMessage()
    {

        $id = $this->Auth->user('id');
        $this->loadModel('Messages');

        $messages = $this->Messages->find('all', array(
            'conditions' => array(
                array('recipient' => $this->Auth->user('id')),
            )
        ));

        $unread_messages = array();
        foreach ($messages as $message) {
            $seen = $message['seen'];

            if ($seen == false || $seen == null) {
                array_push($unread_messages, $message);
            }
        }

//        sending messages from within message view
        $message = $this->Messages->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {

            $message_data = $this->request->getData();
            $message_data['sender'] = $id;
//             $message_data['recipient'] = $id;
            $message_data['sent'] = date('Y-m-d H:i:s');
            $message = $this->Messages->patchEntity($message, $message_data);
            if ($this->Messages->save($message)) {
            } else {
                $this->Flash->error(__('The message could not be sent. Please, try again.'));
            }
        }
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
            if ($user_data['radius'] > 1000000) {
                $user_data['radius'] = 1000000;
            }
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
        $this->loadComponent('Mutual');

        //only get distinct users
        $distinct_users = $related_users_interests->group('Users.id');

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
        $number_of_interests = $interest_count;

        //sort the interests from most to least
        arsort($interest_count);

        //slice the array to get the top 15
        $top_interests = $interest_count;

        //set empty array for users in radius
        $users_in_radius = array();
        $this->loadComponent('Blocked');


        $array_count = 0;
        $list_of_users = array();
        foreach ($top_interests as $top_interest_id => $count) {
            $top_user = $this->Users->get($top_interest_id);
            array_push($list_of_users, $top_user);
        }
        //for each user get the distance from the main user
        foreach ($list_of_users as $distinct_user) {
            $blocked_user = $this->Blocked->blockedUser($distinct_user, $user);
            $blocked_by = $this->Blocked->blockedBy($distinct_user, $user);
            $distance = $this->Distance->getDistance($user['location'], $distinct_user['location']);
            //if the user is within the radius and in the top users array add it to the users in radius var
            if ($distance <= $user['radius'] && $blocked_user == false && $blocked_by == false && $array_count <= 10) {
                array_push($users_in_radius, $distinct_user);
                $array_count++;
            }
        }

        foreach ($users_in_radius as $users_in_radiu) {
            $distance = $this->Distance->getDistance($user['location'], $users_in_radiu['location']);
//            $mutual_interests = $this->Mutual->getMutual($user->interests, )
//                $number_in_common =
            $users_in_radiu['distance'] = $distance;
        }

        $users_in_radius_limit = array_slice($users_in_radius, 0, 10, true);

        //if there are distinct users work out how much space each gets
        if (count($users_in_radius)) {
            $number_of_users = count($users_in_radius);

            $space_allocated = 360 / $number_of_users;
        }

        //would like this working


        $this->loadModel('Messages');
        $message = $this->Messages->newEntity();
        $messages = $this->Messages->find('all', array(
            'conditions' => array(
                array('recipient' => $this->Auth->user('id')),
            )
        ));

        $unread_messages = array();
        foreach ($messages as $message) {
            $seen = $message['seen'];

            if ($seen == false || $seen == null) {
                array_push($unread_messages, $message);
            }
        }


        $this->set(compact('user', 'user_matching_data', 'list_of_users', 'message', 'users_in_radius', 'space_allocated', 'number_of_interests', 'top_interests', 'interest_count', 'unread_messages'));
        $this->set('_serialize', ['user']);
    }

    public function switchTheme() {
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id, [
            'contain' => ['Interests']
        ]);
        if ($user->theme == 'dark') {
            $this->Users->query()->update()
                ->set(['theme' => 'light'])
                ->where(['id' => $id])
                ->execute();
        } else {
            $this->Users->query()->update()
                ->set(['theme' => 'dark'])
                ->where(['id' => $id])
                ->execute();
        }
        return $this->redirect(['action' => 'connections']);
    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout', 'login']);
    }


}
