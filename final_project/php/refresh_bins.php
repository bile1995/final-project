<?php
include 'db.php';

$res = $conn->query("SELECT * FROM bins");
while ($row = $res->fetch_assoc()) {
  $fill_percent = round(($row['current_level'] / $row['capacity']) * 100);
  echo "<tr>
    <td>{$row['bin_id']}</td>
    <td>{$row['location']}</td>
    <td>{$row['waste_type']}</td>
    <td>{$row['capacity']}</td>
    <td>{$row['current_level']}</td>
    <td>{$fill_percent}%</td>
    <td><a href='delete.php?type=bin&id={$row['bin_id']}' class='btn btn-sm btn-danger'>Delete</a></td>
  </tr>";
}
?>
