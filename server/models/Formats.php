<?php
class Formats {
    private $format;
    private $type;
    private $data = array();
    public function __construct($format, $type) {
        if ('' == $format) {
            $this->format = 'json';
        } else {
            $this->format = $format;
        }
        $this->type = $type;
    }
    public function transfer($data) {
        if ('json' == $this->format) {
            $res = $this->returnJSON($data);
            if (!empty($this->type)) {
            return $res;
            } else {
                echo json_encode($res);
            }
        } else if ('xml' == $this->format) {
            $response = new Phalcon\Http\Response();
            $response->setHeader("Content-type", "application/xml");
            $arr = $this->returnJSON($data);
            $obj = new Array2XML();
            $xml = $obj->convert($arr);
            $response->setContent($xml);
            $response->send();
        } else if ('html' == $this->format) {
            $arr = $this->returnJSON($data);
            $this->returnHTML($arr);
        } else {
            $arr = $this->returnJSON($data);
            $this->returnTXT($arr);
        }
    }
    private function returnJSON($autos) {
        if (!empty($this->type)) {
          return  array(
                'id' => $autos->getId(),
                'img' => $autos->getImg(),
                'brand' => $autos->getBrand(),
                'model' => $autos->getModel(),
                'year' => $autos->getYear(),
                'capacity' => $autos->getCapacity(),
                'color' => $autos->getColor(),
                'max_speed' => $autos->getMaxSpeed(),
                'price' => $autos->getPrice()
            );
        } else {
            foreach( $autos as $auto){
                $this->data[] = array(
                    'id' => $auto->getId(),
                    'img' => $auto->getImg(),
                    'brand' => $auto->getBrand(),
                    'model' => $auto->getModel()
                );
            }
            return $this->data;
        }
    }
    
    private function returnHTML($arr) {
        $th = '<tr>';
        $output = '<tr>';
        $count = 1;
            foreach($arr as $key => $value)
            {
                foreach($value as $key => $value)
                {if (1 == $count) {
                    $th .= '<th>'.$key.'</th>';
                }
                    $output .= '<td>'.$value.'</td>';
                }
                $count++;
                $output .= '</tr>';
            }
        $th .= '</tr>';
        $result = "<table class='table-bordered'><tbody>".$th.$output."</tbody></table>";
        echo $result;
    }

    private function returnTXT($arr) {
        echo '<pre>';
        print_r($arr);
    }
}
