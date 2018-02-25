<?php

namespace App\Http\Controllers\Admin;

interface Resource
{
    public function index();
    public function create();
    public function store();
    public function show($id);
    public function edit($id);
    public function update($id);
    public function destroy($id);
}