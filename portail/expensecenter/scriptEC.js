function afficherMasquerRow(id)
{
  if (document.getElementById(id).style.display == "none")
    document.getElementById(id).style.display = "table-row";
  else
    document.getElementById(id).style.display = "none";
}
