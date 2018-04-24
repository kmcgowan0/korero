<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;

/**
 * UsersInterests Controller
 *
 * @property \App\Model\Table\UsersInterestsTable $UsersInterests
 *
 * @method \App\Model\Entity\UsersInterest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersInterestsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Interests']
        ];
        $usersInterests = $this->paginate($this->UsersInterests);

        $this->set(compact('usersInterests'));
    }

    /**
     * View method
     *
     * @param string|null $id Users Interest id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usersInterest = $this->UsersInterests->get($id, [
            'contain' => ['Users', 'Interests']
        ]);

        $this->set('usersInterest', $usersInterest);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usersInterest = $this->UsersInterests->newEntity();
        if ($this->request->is('post')) {

                $usersInterest = $this->UsersInterests->patchEntity($usersInterest, $this->request->getData());

                if ($this->UsersInterests->save($usersInterest)) {
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The users interest could not be saved. Please, try again.'));

            }

        $users = $this->UsersInterests->Users->find('list', ['limit' => 200]);
        $interests = $this->UsersInterests->Interests->find('list', ['limit' => 200]);
        $this->set(compact('usersInterest', 'users', 'interests'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Users Interest id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersInterest = $this->UsersInterests->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usersInterest = $this->UsersInterests->patchEntity($usersInterest, $this->request->getData());
            if ($this->UsersInterests->save($usersInterest)) {
                $this->Flash->success(__('The users interest has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users interest could not be saved. Please, try again.'));
        }
        $users = $this->UsersInterests->Users->find('list', ['limit' => 200]);
        $interests = $this->UsersInterests->Interests->find('list', ['limit' => 200]);
        $this->set(compact('usersInterest', 'users', 'interests'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Users Interest id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usersInterest = $this->UsersInterests->get($id);
        if ($this->UsersInterests->delete($usersInterest)) {
            $this->Flash->success(__('The users interest has been deleted.'));
        } else {
            $this->Flash->error(__('The users interest could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
