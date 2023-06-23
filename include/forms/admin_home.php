<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2023 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>

<h1>How to configure this application</h1>
<p>From this Admin area you can configure the <b>DaDaBIK</b> application you have created 

<?php if ($orazio_edition === 1){
    echo ' with <b>'.$orazio_name.'</b>';
} ?>
.</p>

<h2>The standard CRUD pages</h2>

<?php if ($orazio_edition === 1){ ?>

<p>DaDaBIK has created a <b>Table</b> and <strong>CRUD Page</strong> for each <i>entity</i> you need in your application (e.g. if you are building a CRM, one of your entities will be <i>customers</i>) ; this page contains: a standard report (a results grid that represents the records you have in your table), the forms needed to: insert, edit and filter records and some additional tools that allow you to generate graph, pivot and PDF reports.</p>

<?php } else{ ?>
<p>During the <i>classic</i> installation process, DaDaBIK by default creates a <strong>CRUD page</strong> for each table belonging to your database (or for each sheet in your Excel file); this page contains: a standard report (a results grid that represents the records you have in your table), the forms needed to: insert, edit and filter records and some additional tools that allow you to generate graph, pivot and PDF reports.</p>
<?php } ?>

<?php if ($orazio_edition === 0){ ?>

<p>If, instead, you have built a <strong>blank</strong> application (i.e. based on an empty database), the first thing you have to do is create one or more tables using the <a href="data.php">Data</a> tab. DaDaBIK will guide you through the <i>installation</i> of these tables and, even in this case, for each table, a <strong>CRUD page</strong> will be created.</p>

<?php } ?>

<h2>Customization</h2>

<p>You can <strong>customize those pages</strong> in several ways, here is something you might want to try:<p>

<ul>

<li>You want to change a label or a field type? Use the <a href="internal_table_manager.php">Forms Configurator</a></li>

<li>You want to hide a field from a form? Use the <a href="permissions_manager.php?function=configure">Permissions Manager</a>, choose the users group you want to set the permissions for and set NO for any particular field/form combination you want to remove.</li>

<li>You want to re-arrange the pages in your Menu? Use the <a href="tables_inclusion.php">Pages</a> tab.</li>

<?php if ($orazio_edition === 0){ ?>

<li>You want to synchronize your application after having modified your DB schema (e.g. you added a new field to a table)? Use  <a href="db_synchro.php">DB Synchro</a>.</li>

<?php } ?>

</ul>

<p>The <a href="internal_table_manager.php">Forms Configurator</a> and the <a href="permissions_manager.php">Permissions Manager</a> will probably cover most of your customization needs. Consider the use of <strong>lookup fields</strong> (in Forms Configurator choose select_single as field type and see the lookup parameters), <strong>calculated fields</strong> and <strong>custom formatting functions</strong>, which are very powerful tools.</p>

<h2>Tables</h2>

<p>If you need additional tabels or want to add/edit existing tables you can do it from the <a href="data.php">Data</a> tab.</p>


<h2>Views</h2>

<p>Sometimes the default pages created by DaDaBIK (one for each table) are not enough, maybe you need a report page based on two or more tables and the lookup fields can't solve your needs, or you might need particular filters. In theses case you can simply crate a <strong>VIEW</strong>, DaDaBIK will create the corresponding page in your application exactly as it does it for tables. You can do that from the <a href="data.php">Data</a> tab.</p>


<h2>Custom pages</h2>

<p>Finally, you can also create completely custom HTML, Javascript or PHP pages, write your own code and add the pages to your DaDaBIK applications, these are called <strong>custom pages</strong> and you can set them from the	 <a href="tables_inclusion.php">pages</a> section.</p>


<h2>Additional config parameters</h2>

<p>Consider that some additional general configuration parameters can be directly set from the file /include/config_custom.php, just by opening it with a plain text editor and editing it. At that level you can, for example, enable/disable/configure some DaDaBIK features (e.g. authentication, upload, email notices, language, ...).</p>
    
<p>For additional and more specific information, including instructions to master advanced features such as <strong>custom buttons</strong>, <strong>Hooks</strong>, <strong>PDF templates</strong> and others, please refer to the <a href="https://dadabik.com/index.php?function=show_documentation" target="_blank">DaDaBIK documentation</a></p>

<p>You can also follow one of our vidoe tutorials, for example the "Create a Custom CRM Application in 2 hours without coding" tutorial.

<p>Have fun :)</p>


<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/aTSTzxp0_qc" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

