
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel = "stylesheet" type="text/css" media="screen" href = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap.css">
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap.css">
        <link rel = "stylesheet" href = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap-table.css">
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/js/jquery-2.1.4.js"></script>
        <!--<script type = "text/javascript" src = "<?php echo base_url(); ?>publico/jquery-ui-1.11.4/jquery-ui.js"></script>-->
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap.js"></script>
        <script type = "text/javascript" src = "<?php echo base_url(); ?>publico/bootstrap-table-master/src/bootstrap-table.js"></script>
    </head>
    <body>
        <div class = "login col-md-4 mx-auto text-center">
            <h1>Login Administrador</h1>
            <form method = "post" action = "<?php echo site_url('login/verificar'); ?>">
                <div class = "form-group">
                    <input type = "text" name = "usuario" placeholder = "Usuario" class = "form-control">
                </div>
                <div class = "form-group">
                    <input type = "password" name = "contraseña" placeholder = "Contraseña" class = "form-control">
                </div>
                <div class = "form-group">
                    <?php if ($this->session->flashdata('msg')): ?>
                        <p style = "color: red;"><?php echo $this->session->flashdata('msg'); ?></p>
                    <?php endif; ?>
                </div>
                <div class = "form-group">
                    <input type = "submit" name = "btningresar" value = "Ingresar" class = "btn btn-primary">
                </div>                
            </form>
        </div>

    </body>
</html>