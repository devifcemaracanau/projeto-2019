<?php

/**
 * Rotas da API.
 * Todas as rotas são prefixadas com "api/v1"
 * e os controllers estão no namespace "App\Http\Controllers\Api"
 */

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('dados-basicos', 'DadosBasicosController@index');

// Rotas de Estado
$router->get('estados', 'EstadosController@index');
$router->get('estados/{id_estado}/municipios','EstadosController@getMunicipios');

//Rotas de município
$router->get('municipios','MunicipiosController@index');
$router->get('municipios/{id_municipio}/instituicoes','MunicipiosController@getInstituicoes');
$router->get('municipios/{id_municipio}/tipos-instituicoes','MunicipiosController@getTiposInstituicoes');
$router->get('municipios/{id_municipio}/bairros','MunicipiosController@getBairros');
$router->get('municipios/{id_municipio}/bairro/{bairro}/instituicoes','MunicipiosController@getInstituicoesBairro');
$router->get('municipios/{id_municipio}/bairro/{bairro}/tipos-instituicoes','MunicipiosController@getTiposInstituicoesBairro');

//Rotas de instituilçao
$router->get('instituicoes','InstituicoesController@index');
$router->get('instituicao/{id_instituicao}/profissionais','InstituicoesController@getProfissionais');

//Rotas de tipos de instituilções
$router->get('tipos-instituicoes','TiposInstituicoesController@index');

//Respostas do formulário
$router->options('respostas', function () {
    return response('', 200);
});
$router->post('respostas','RespostasController@save');
