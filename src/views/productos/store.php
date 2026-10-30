<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="POST" action="/productos">
<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">nombre del producto</label>
  <input type="text" class="form-control" name="nombre" id="exampleFormControlInput1">
</div>

<div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">precio</label>
  <input type="number" class="form-control" name="precio" id="exampleFormControlInput1">
</div>

<div class="mb-3">
  <label for="exampleFormControlTextarea1" class="form-label">descripción</label>
  <textarea class="form-control" name="descripcion" id="exampleFormControlTextarea1" rows="3"></textarea>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>