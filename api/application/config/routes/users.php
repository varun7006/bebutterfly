<?php

$route['users'] = 'users/User';

$route['user/getuserlist'] = 'users/User/getUserList';
$route['user/savenewuser'] = 'users/User/saveNewUser';
$route['user/updateuser'] = 'users/User/updateUser';
$route['user/deleteuser'] = 'users/User/deleteUser';
$route['user/saveexcel']  = 'users/User/saveExcel';
$route['users/addusers'] = 'users/User/addNewUserView';
$route['users/generateuserexcel'] = 'users/User/generateUserExcel';
