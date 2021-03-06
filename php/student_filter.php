<?php
include_once 'db.php';
include 'model/SimpleResponse.php';
include 'model/MobileScheduleResponse.php';

header("Content-Type: application/json");

$student_group_id = $_REQUEST['group_id'];
$day = $_REQUEST['day'];

if(isset($student_group_id) && isset($day)){
    $response = getStudentScheduleOfDay($student_group_id,$day);
    echo $response->get_JSON();
}else{
    $response = new SimpleResponse(400,"Bad Request");
    echo $response->get_JSON();
}


function getStudentScheduleOfDay($group_id,$day){
    $dbconnection= establishConnectionDB();
    $response = new SimpleResponse(403,"Oops, Something Went Wrong");
    $horarios_alumno = $dbconnection->select("horario",[
        "[<]materia"=>["clave_materia" => "clave_materia"],
        "[<]maestro"=>["clave_maestro" => "clave_maestro"]
    ],[
        "materia.nombre_materia",
        "maestro.nombre_maestro",
        "horario.clave_aula",
        "horario.hora_inicio",
        "horario.hora_termina"
    ],[
        "horario.clave_grupo" => $group_id,
        "horario.dia_semana" => $day
    ]);
    if(!$horarios_alumno){
        $response -> set_message("No Schedules Found At " . $day);
    }else{
        $response = new MobileScheduleResponse(200,"Schedules Found", $horarios_alumno);
    }
    return $response;
}

?>