<?php
include('inc.inc.php');
$self = str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")));
$title = ucfirst($self);
$section = "prod";
$tabla = $pre.$section;

  $date =time ();

  $meses = array('1' => 'Enero',
                 '2' => 'Febrero',
                 '3' => 'Marzo',
                 '4' => 'Abril',
                 '5' => 'Mayo',
                 '6' => 'Junio',
                 '7' => 'Julio',
                 '8' => 'Agosto',
                 '9' => 'Septiembre',
                 '10' => 'Octubre',
                 '11' => 'Noviembre',
                 '12' => 'Diciembre');

  $dias = array('1' => 'Lunes',
                '2' => 'Martes',
                '3' => 'Mi�rcoles',
                '4' => 'Jueves',
                '5' => 'Viernes',
                '6' => 'S�bado',
                '7' => 'Domingo');

  $hoy = date('d', $date);
  $dia_hoy = date('N', $date);
  $mes = date('m', $date);
  $anio = date('Y', $date);
  $hora = date('H', $date);
  $minut = date('i', $date);

disp_header($pre,$title);

   if (isset($_GET['cat'])){$categ_id = str_input($_GET['cat']);}
   else {$categ_id='1';}
// obtener el valor de $st para paginacion
if(isset($_GET['st'])){$st = str_input($_GET['st']);} else{$st = 0;}

//$secc_princ = mysql_query("SELECT ".$secc." FROM ".$pre."info") or die(mysql_error());
//  $txt = str_output(mysql_result($secc_princ,0,$secc));


// Categor�as de productos
     $cat_productos = mysql_query("SELECT cat_id,cat_productos FROM ".$pre."cat WHERE cat_productos IS NOT NULL ORDER BY cat_id") or die(mysql_error());
        for($j=0; $j<mysql_num_rows($cat_productos); ++$j) {
          $cat_id = mysql_result($cat_productos,$j,"cat_id");
          $cat_prod[$cat_id] = str_output(mysql_result($cat_productos,$j,"cat_productos"));
        }
      asort($cat_prod);
      //echo disp_array_asoc($cat_prod);
/*  $bin_array = array('si' => 'S�','no' => 'No');      */

  $url_images_folder = "./".str_replace(".","",strrev(strrchr(strrev(basename($_SERVER['PHP_SELF'])),".")))."/";
  $url_thumbs_folder = $url_images_folder."thumbs/";

?>
<div id="content">

      <?php if (!empty($_SESSION)) {echo "<div class=\"box_warning\">".disp_array_asoc($_SESSION)."</div>";} ?>
<?php
    if (isset($_GET['prod_id'])){$secc_name = "Destacados";}
    else {foreach ($cat_prod as $key => $value) {if ($key == $categ_id) {$secc_name = $value;}}}
?>
  <h1><?php echo $secc_name; ?></h1>
    <div class="article">

    <p>El agua de Aqua3 sigue estrictos est�ndares de calidad, que aseguran su limpieza y pureza en todo momento.</p>
    <p>Aqua3 viene en distintos formatos, para que pueda disfrutarla en cualquier momento o lugar.</p>
<?php

  $datos = mysql_query("SELECT prod_id FROM ".$tabla." WHERE prod_cat='".$categ_id."' AND prod_pub='si'") or die(mysql_error());
  if (mysql_num_rows($datos) == 0) {
    draw_noitems();
  }
  else {
    if (isset($_GET['prod_id'])){
      $prod_id = str_input($_GET['prod_id']);
      $datos = mysql_query("SELECT * FROM ".$tabla." WHERE prod_id='".$prod_id."' AND prod_pub='si'") or die(mysql_error());
      $total = 1;
    }
    else {
  $datos = mysql_query("SELECT COUNT(*) FROM ".$tabla." WHERE prod_cat='".$categ_id."' AND prod_pub='si'") or die(mysql_error());
  $total = mysql_result($datos,0);
  $datos = mysql_query("SELECT * FROM ".$tabla." WHERE prod_cat='".$categ_id."' AND prod_pub='si' ORDER BY prod_id DESC LIMIT ".$st.",".$pp_pub."") or die(mysql_error());
    }

      $num_rows = mysql_num_rows($datos);
      for($j=0; $j<$num_rows; ++$j) {
          $celda = (($j % 2) == 0) ? "celda1" : "celda2";
          $id = mysql_result($datos,$j,"prod_id");
          $prod_nombre = str_output(mysql_result($datos,$j,"prod_nombre"));
          $prod_imagen = str_output(mysql_result($datos,$j,"prod_imagen"));
          $prod_resena = str_output(mysql_result($datos,$j,"prod_resena"));
          $prod_cat = str_output(mysql_result($datos,$j,"prod_cat"));
          $prod_pub = str_output(mysql_result($datos,$j,"prod_pub"));
          $prod_dest = str_output(mysql_result($datos,$j,"prod_dest"));
          $prod_fecha = str_output(mysql_result($datos,$j,"prod_fecha"));

      $nombre_prod_dia = date('N',$prod_fecha);
      $prod_mes = date("m",$prod_fecha);
      $prod_dia = date("d",$prod_fecha);
      $prod_anio = date("Y",$prod_fecha);

      foreach ($dias as $key => $value) { if ($key == $nombre_prod_dia) {$fecha = $value.", ";} }
      $fecha .= $prod_dia;
      $fecha .= " de ";
      foreach ($meses as $key => $value) { if ($key == $prod_mes) {$fecha .= $value;} }
      $fecha .= " de ".$prod_anio;
?>


<div align="left">
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"> <h2><?php echo $prod_nombre; ?></h2></td>
  </tr>
  <tr>
    <td valign="top" width="140" align="center">
              <a href="<?php echo $url_images_folder."index.php?&amp;act=v&amp;f=".$prod_imagen; ?>" title="<?php echo $prod_nombre; ?>">
              <img src="<?php echo $url_thumbs_folder.$prod_imagen; ?>" border="0" align="center" alt="<?php echo $prod_nombre; ?>" /></a>
    </td>
    <td valign="top">
              <div class="descripcion"><?php echo $prod_resena; ?></div>
    </td>
  </tr>
</table>
</div>

<?php     }
    echo paginar($total,$pp_pub,$st,$thisurl."?&amp;cat=".$categ_id."&amp;st=");
  }
?>
     </div>
</div>
<?php include('menu.inc.php'); ?>
<?php include('side.inc.php'); ?>
<?php disp_footer($pre); ?>