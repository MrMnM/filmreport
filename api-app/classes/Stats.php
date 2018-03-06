<?php
class Stats
{
    public function __construct($container)
    {
        $this->db = $container->get('database');
        $this->auth = $container->get('auth');
    }

    public function linechart($request, $response, $args)
    {
        $this->auth->check();
        $start = new DateTime($request->getQueryParam('start'));
        $end = new DateTime($request->getQueryParam('end'));
        $indata = $this->db->select('projects', [
                            'tot_money',
                            'p_end'
                          ], [
                            "user_id" => $_SESSION['user'],
                            "p_end[<>]" => [$start->format('Y-m-d'), $end->format('Y-m-d')],
                            "ORDER" => "p_end",
                          ]);

        $allDates=[];
        $i=0;

        foreach ($indata as $in) {
            $allDates[$i][0]=new DateTime($in["p_end"]);
            $allDates[$i][1]=$in["tot_money"];
            $i++;
        }

        if (count($allDates)==0) {
            $allDates[$i][0]=$end;
            $allDates[$i][1]=0;
        }

        $monthsDifference = ($end->diff($start)->m + ($end->diff($start)->y*12))+1;
        $out = array_pad(array([0,0]), $monthsDifference, [0,0]);

        $j=0;
        while ($start < $end) {
            for ($i=0; $i<count($allDates) ; $i++) {
                if ($allDates[$i][0]->format('Y-m')==$start->format('Y-m')) {
                    $out[$j][0]+=$allDates[$i][1];
                    $out[$j][1]=$start->format('Y-m');
                } else {
                    $out[$j][1]=$start->format('Y-m');
                }
            }

            $start->modify('+1 month');
            $j++;
        }

        $o=[];
        foreach ($out as $v) {
            $to = ['period'=>$v[1],'Pay'=>$v[0]];
            array_push($o, $to);
        }

        return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                         ->withHeader('Access-Control-Allow-Credentials', 'true')
                         ->withJson($o);
    }

    public function donutchart($request, $response, $args)
    {
        $this->auth->check();
        $indata = $this->db->select('projects', [
                            "[>]companies" => ["p_company" => "company_id"]
                          ], [
                            'projects.tot_money',
                            'companies.name'
                          ], [
                            "user_id" => $_SESSION['user']
                          ]);
        $o = [];
        $lastcompany ="";
        $totalmoney=0;

        foreach ($indata as $cur) {
            $money = $cur["tot_money"];
            $company = $cur["name"];
            if ($lastcompany==$company) {
                $totalmoney=$totalmoney + $money;
                $lastcompany=$company;
            } else {
                if ($lastcompany!="") {
                    $to = ['label'=>$lastcompany,'value'=>(int)$totalmoney];
                    array_push($o, $to);
                }
                $totalmoney=$money;
                $lastcompany=$company;
            }
        }

        usort($o, function ($a, $b) {
            return $a['value'] - $b['value'];
        });

        return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                       ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                       ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                       ->withHeader('Access-Control-Allow-Credentials', 'true')
                       ->withJson($o);
    }

    public function misc($request, $response, $args)
    {
        $this->auth->check();
        $start = new DateTime($request->getQueryParam('start'));
        $end = new DateTime($request->getQueryParam('end'));
        $indata = $this->db->select('projects', [
                            'tot_money',
                            'p_end'
                          ], [
                            "AND" => [
                            "user_id" => $_SESSION['user'],
                            "p_end[<>]" => [$start->format('Y-m-d'), $end->format('Y-m-d')],
                          ],
                            "ORDER" => "p_start"
                          ]);

$monthsDifference =($end->diff($start)->m + ($end->diff($start)->y*12))+1;

$total =0;
foreach ($indata as $cur) {
  $total = $total + $cur["tot_money"];
}


$active = $this->db->count('projects', [
                    "AND" => [
                    "user_id" => $_SESSION['user'],
                    "p_finished" => 0
                    ]
                  ]);

$o = ['mean_month'=>round($total/$monthsDifference),
      'active_projects'=>$active];


        return $response ->withHeader('Access-Control-Allow-Origin', 'https://filmstunden.ch')
                       ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                       ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                       ->withHeader('Access-Control-Allow-Credentials', 'true')
                       ->withJson($o);
    }
}
