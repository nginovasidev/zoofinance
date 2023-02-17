<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<style type="text/css">
	.form-entry input {
  border: 1px solid #ccc;
  width: 75%;
  line-height: 2em;
  padding-left: 1em;
}

.form-entry button {
  border: 0;
  background-color: #5AD0A1;
  color: #fff;
  cursor: pointer;
  padding: .6em 1.25em;
}

.table-wrapper {
  font-family: Arial, sans-serif;
}

.table-wrapper table td {
  border-bottom: 1px dashed #ccc;
  padding: 1em .2em;
}

.table-wrapper table tr:last-child td {
  border-bottom: 0;
}

.table-wrapper table td:first-child {
  color: #ccc;
  width: 2em;
}

.table-wrapper table td:last-child a {
  color: #F00;
  text-decoration: none;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="form-entry">
  <form>
    <input type="text" id="add-service" placeholder="Enter your service name here..." />
    <button type="submit" id="add-service-button">Add</button>
  </form>
</div>
<div class="table-wrapper">
  <table id="service-table" width="100%" border="1">
    <thead>
      <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<script type="text/javascript">
	$(function() {
  function numberRows($t) {
    var c = 0;
    $t.find("tbody tr").each(function(ind, el) {
      $(el).find("td:eq(0)").html(++c + ".");
    });
  }


  $("#add-service-button").click(function(e) {
    e.preventDefault();
    var $row = $("<tr>");
    $row.append($("<td>"));
    $row.append($("<td>").html($("#add-service").val()));
    $row.append($("<td>").html("<a class='del-service' href='#' title='Click to remove this entry'>X</a>"));
    $row.appendTo($("#service-table tbody"));
    numberRows($("#service-table"));
  });


  $("#form-entry form").submit(function(e) {
    e.preventDefault();
    $("#add-service-button").trigger("click");
  });

  
  $("#service-table").on("click", ".del-service", function(e) {
    e.preventDefault();
    var $row = $(this).parent().parent();
    var retResult = confirm("Are you sure you wish to remove this entry?");
    if (retResult) {
      $row.remove();
      numberRows($("#service-table"));
    }
  });
});
</script>
</body>
</html>