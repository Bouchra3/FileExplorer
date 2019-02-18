<?php
  // $current représente le chemin en cours (sans le dosssier racine "storage")
  // $parent représente le chemin du dossier parent (s'il existe)
  if (!isset($_REQUEST["current"]))
  {
    $current = "";
    $parent = NULL;
  } else {
    $current = $_REQUEST["current"];
    $parent = dirname($current);

    if ($current == $parent)
    {
      $parent = NULL;
    }
  }

  // $directoryRealPath représente le chemin physique
  $directoryRealPath = realpath("../storage" . $current);

  // s'il s'agit d'un fichier on lance son téléchargement
  if (is_file($directoryRealPath))
  {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($current).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($directoryRealPath));
    readfile($directoryRealPath);
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Filer</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.css" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>
<body>
  <section class="section">
  <div class="container">
    <h1 class="title">Mon filer</h1>
    <div class="columns">
      <div class="column">
        <table class="table is-fullwidth is-hoverable">
<?php
    // on affiche le lien pour le dossier parent s'il existe
    if ($parent != NULL)
    {
?>
            <tr>
              <td><i class="fas fa-level-up-alt"></i></td>
              <td><a href="layout.php?current=<?= urlencode($parent) ?>">Parent</a></td>
              <td></td>
            </tr>
<?php
    }

    // on affiche la liste des fichiers / sous-dossiers
    foreach (scandir($directoryRealPath) as $item)
    {
      if ($item == "." || $item == "..")
      {
        continue;
      }

      $itemRealPath = $directoryRealPath . "/" . $item;
      $itemLogicalPath = $current . "/" . $item;
      if (is_dir($itemRealPath))
      {
?>
            <tr>
              <td><i class="fas fa-folder"></i></td>
              <td><a href="layout.php?current=<?= urlencode($itemLogicalPath) ?>"><?= $item ?></a></td>
              <td></td>
            </tr>
<?php                
      } else {
?>
            <tr>
              <td><i class="fas fa-file"></i></td>
              <td><a href="layout.php?current=<?= urlencode($itemLogicalPath) ?>"><?= $item ?></a></td>
              <td>
                <form>
                  <button class="button is-light is-small" type="submit"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
<?php 
      }
    }
?>
          </table>
        </div>
        <div class="column">
          <form>
            <div class="field">
              <label class="label">Nouveau dossier</label>
              <div class="control">
                <input class="input" type="text" placeholder="Nom du dossier">
              </div>
            </div>
            <div class="field is-grouped">
              <div class="control">
                <button class="button is-link">Créer</button>
              </div>
            </div>
          </form>
          <hr />
          <form>
            <div class="field">
              <label class="label">Nouveau fichier</label>
              <div class="control">
                <input class="input" type="text" placeholder="Nom du fichier">
              </div>
            </div>
            <div class="field">
              <label class="label">Contenu</label>
              <div class="control">
                <textarea class="textarea" placeholder="Textarea"></textarea>
              </div>
            </div>
            <div class="field is-grouped">
              <div class="control">
                <button class="button is-link">Enregistrer</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</body>
</html>