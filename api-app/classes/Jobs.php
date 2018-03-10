<?php
class Jobs
{
    public function __construct($container)
    {
        //$this->db = $container->get('database');
    }

    public function get($request, $response, $args)
    {
        $data=[
        "ProduktionsleiterIn",
        "Produktions-AssistentIn",
        "Produktions-SekretärIn",
        "AufnahmeleiterIn",
        "Set-AufnahmeleiterIn",
        "Aufnahmeleiter-AssistentIn",
        "Regie-AssistentIn",
        "2. RegieassistentIn",
        "Continuity",
        "Chef-Kameramann",
        "SchwenkerIn",
        "Kamera-Assistent",
        "2. Kamera-AssistentIn",
        "DIT",
        "Video-TechnikerIn",
        "Chef-BeleuchterIn",
        "BeleuchterIn",
        "Key Grip",
        "Grip",
        "TonmeisterIn",
        "TonoperateurIn",
        "Perche",
        "Ausstattungsleitung",
        "AusstatterIn",
        "AusstattungsassistentIn",
        "RequisiteurIn",
        "Decorbau",
        "KostümbildnerIn",
        "Kostüm AssistentIn",
        "Garderobe",
        "Chef-MaskenbildnerIn",
        "MaskenbildnerIn",
        "Maskenbildner-Assistentin",
        "Hair-StylistIn",
        "Chef-Editor",
        "Editor",
        "Ton-Editor",
        "Editor-AssistentIn",
      ];
        $response2 = $response->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                         ->withHeader('Access-Control-Allow-Credentials', 'true')
                         ->withJson($data);
        return $response2;
    }
}
