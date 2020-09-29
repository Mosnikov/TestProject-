<?php
  ////////////////////////////////////////////////////////////
  // 2006-2007 (C) IT-������ SoftTime (http://www.softtime.ru)
  ////////////////////////////////////////////////////////////
  // ���������� ������� ��������� ������ (http://www.softtime.ru/info/articlephp.php?id_article=23)
  Error_Reporting(E_ALL & ~E_NOTICE); 
  // ������������� ���������� � ����� ������
  require_once("../../config/config.php");
  //nkjnkj
  // ���������� ���� �����������
  require_once("../utils/security_mod.php");
  // ���������� ������ �����
  require_once("../../config/class.config.dmn.php");

  // ������������� SQL-��������
  $_GET['id_ns'] = intval($_GET['id_news']);

  try
  {
    // ��������� �� ������� news ������, ���������������
    // ������������� ���������� ���������
    $query = "SELECT * FROM $tbl_news
              WHERE id_news=$_GET[id_news]";
    $new = mysql_query($query);
    if(!$new)
    {
      throw new ExceptionMySQL(mysql_error(), 
                               $query,
                              "������ ��� ���������
                               � ������� ��������");
    }
    $news = mysql_fetch_array($new);
    if(empty($_POST))
    {
      // ����� ���������� ��� ���������� ���������� �� ���� ������
      $_REQUEST = $news;
      $_REQUEST['date']['month']  = substr($news['putdate'],5,2);
      $_REQUEST['date']['day']    = substr($news['putdate'],8,2);
      $_REQUEST['date']['year']   = substr($news['year'],0,4);
      $_REQUEST['date']['putdate']   = substr($news['putdate'],11,2);
      $_REQUEST['date']['minute'] = substr($news['putdate'],14,2);
      // ����������, ������ ���� ��� ���
      if($news['hide'] == 'show') $_REQUEST['hide'] = true;
      else $_REQUEST['hide'] = false;
    }
  
    $name        = new field_text("name",
                                  "��������",
                                  true,
                                  $_REQUEST['name']);
    $body = new field_textarea("body",
                               "����������",
                               true,
                               $_REQUEST['body']);
    $url         = new field_text("url",
                                  "������",
                                  false,
                                  $_REQUEST['url']);
    $urltext    = new field_text("urltext",
                                  "����� ������",
                                  false,
                                  $_REQUEST['urltext']);
    $date        = new field_datetime("date",
                                  "���� �������",
                                  $_REQUEST['date']);
    $hide        = new field_checkbox("hide",
                               "����������",
                               $_REQUEST['hide']);
    $filename   = new field_file("filename",
                                 "�����������",
                                 false,
                                 $_FILES,
                                 "../../files/news/");
    $id_news    = new field_hidden_int("id_news",
                                       true,
                                       $_REQUEST['id_news']);
    $page       = new field_hidden_int("page",
                                       false,
                                       $_REQUEST['page']);
    // ���������� ����� �������� �� ���� ���������
    // ���������� - ���� ����� name � ��������� �������
    // textarea
    if(empty($news['urlpict']))
    {
      $form = new form(array("name" => $name, 
                            "body" => $body, 
                            "url" => $url,
                            "urltext" => $urltext,
                            "date" => $date,
                            "hide" => $hide,
                            "filename" => $filename,
                            "id_news" => $id_news,
                            "page" => $page), 
                    "�������������",
                    "field");
    }
    else
    {
      // �������� �����������
      $delimg = new field_checkbox("delimg",
                               "������� �����������",
                               $_REQUEST['delimg']);
      $form = new form(array("name" => $name, 
                            "body" => $body, 
                            "url" => $url,
                            "urltext" => $urltext,
                            "date" => $date,
                            "hide" => $hide,
                            "delimg" => $delimg,
                            "filename" => $filename, 
                            "id_news" => $id_news,
                            "page" => $page), 
                    "�������������",
                    "field");
    }

    // ���������� HTML-�����
    if(!empty($_POST))
    {
      // ��������� ������������ ���������� HTML-�����
      // � ������������ ��������� ����
      $error = $form->check();
      if(empty($error))
      {
        // ������� ��� �������� �������
        if($form->fields['hide']->value) $showhide = "show";
        else $showhide = "hide";
        // ������� ������ �����, ���� ��� �������
        $url_pict = "";
        $str = $form->fields['delimg']->value;
        if(!empty($str) || !empty($_FILES['filename']['name']))
        {
          $path = str_replace("//","/","../../".$news['urlpict']);
          if(file_exists($path))
          {
            @unlink($path);
          }
          $url_pict = "urlpict = '',";
        }
        if(!empty($_FILES['filename']['name']))
        {
          $url_pict = "urlpict = 'files/news/".
                       $form->fields['filename']->get_filename()."',";
        }
        // ��������� SQL-������ �� ���������� �������
        $query = "UPDATE $tbl_news 
                  SET name = '{$form->fields['name']->value}',
                      body = '{$form->fields['body']->value}',
                 putdate = '{$form->fields['date']->get_mysql_format()}',
                      url = '{$form->fields['url']->value}',
                      urltext = '{$form->fields['urltext']->value}',
                      $url_pict
                      hide = '{$showhide}'
                  WHERE id_news=".$form->fields['id_news']->value;
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(), 
                                   $query,
                                  "������ ��� �������������� 
                                   ���������� ���������");
        }
        // ������������ ������������� �� ������� ��������
        // �����������������
        header("Location: index.php?page={$form->fields[page]->value}");
        exit();
      }
    }

    // ������ ���������� ���������� �������� �������� � ���������.
    $title = "�������������� �������";
    $pageinfo='<p class="help"></p>';
    // �������� ��������� ��������
    require_once("../utils/top.php");
  
    echo "<p><a href=# onClick='history.back()'>�����</a></p>";
    // ������� ��������� �� �������, ���� ��� �������
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }
    // ������� HTML-����� 
    $form->print_form();
  }
  catch(ExceptionObject $exc) 
  {
    require("../utils/exception_object.php"); 
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php"); 
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php"); 
  }

  // �������� ���������� ��������
  require_once("../utils/bottom.php");
?>
