<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

interface Resource
{
    public function index();
    public function create();
    public function store(Request $request);
    public function show($id);
    public function edit($id);
    public function update($id, Request $request);
    public function destroy($id);
}