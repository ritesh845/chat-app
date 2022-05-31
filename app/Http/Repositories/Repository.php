<?php

namespace App\Http\Repositories;

class Repository {

    protected $model;
    protected $model_name = '';

    public function __construct() {
        $this->model = new $this->model_name;
    }

    public function create(array $inputs) {
        return $this->model->create($inputs);
    }

    public function getById($id) {
        return $this->model->findOrFail($id);
    }

    public function update($id, array $inputs) {
        return tap($this->getById($id))->update($inputs)->fresh();
    }

    public function delete($id) {
        return $this->getById($id)->delete();
    }

    public function deleteAll(array $ids) {
        return $this->model->destroy($ids);
    }

    public function all() {
        return $this->model->all();
    }

    public function getByEmail($email) {
        return $this->model->where('email', $email)->first();
    }

}
