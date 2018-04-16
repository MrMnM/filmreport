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
        return $response->withJson($data);
    }
}
