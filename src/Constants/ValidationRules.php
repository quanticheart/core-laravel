<?php

namespace Quanticheart\Laravel\Constants;

class ValidationRules
{
    const ID = [
        'id' => 'required',
    ];

    const NEW_USER = [
        'name' => 'required|min:2|max:80',
        'surname' => 'required|min:2|max:80',
        'cell_phone' => 'required|celular_com_ddd',
        'password' => 'required',
        'email' => 'required|email|unique:users,email'
    ];

    const NEW_USER_ADMIN = [
        'name' => 'required|min:2|max:80',
        'surname' => 'required|min:2|max:80',
        'cell_phone' => 'required|celular_com_ddd',
        'password' => 'required',
        'level' => 'required',
        'email' => 'required|email|unique:users,email'
    ];

    const UPDATE_USER = [
        'name' => 'required|min:2|max:80',
        'surname' => 'required|min:2|max:80',
        'cell_phone' => 'required|celular_com_ddd',
    ];

    const UPDATE_USER_COLOR = [
        'color_id' => 'required',
    ];

    const UPDATE_USER_PASSWORD = [
        'password' => 'required',
    ];

    const UPDATE_USER_LEVEL = [
        'id' => 'required',
        'level' => 'required',
    ];

    const LOGIN = [
        'email' => 'required|email|max:255',
        'password' => 'required'
    ];

    const REMOTE_LOGIN = [
        'token' => 'required'
    ];

    const INSERT_USER_TOKEN = [
        'token' => 'required'
    ];

    const SEND_PUSH_USER = [
        'title' => 'required|min:2|max:180',
        'message' => 'required|min:2|max:180',
    ];
}
