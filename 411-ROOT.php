<?php 

///////////////////////////////////////////////////////////////////////////
//
//
//              $$\   $$\         $$\         $$\         
//              $$ |  $$ |      $$$$ |      $$$$ |        
//              $$ |  $$ |      \_$$ |      \_$$ |        
//              $$$$$$$$ |$$$$$$\ $$ |$$$$$$\ $$ |        
//              \_____$$ |\______|$$ |\______|$$ |        
//                    $$ |        $$ |        $$ |        
//                    $$ |      $$$$$$\     $$$$$$\       
//                    \__|      \______|    \______|    
//
//                        Directory Assistance
//                                 by
//                        Anselmo Fresquez III
//
///////////////////////////////////////////////////////////////////////////

/// 1) Put 411-ROOT.php  into the root folder of your site. This has nothing to do
///    with your actual document root, this can be wherever you want. 
///    Typically, your index.php would go in this folder.
///
/// 2) You need to include 411-ROOT.php in another php file in the same folder
///    and call the function Indexify() and then watch the magic. All you have
///    to do is open Indexify.php to do this, if it was included with your download.
///
/// 3) You'll see a listing of all the directories and their names in ALL CAPS. 
///    these names will become shortcuts as you will see.
///
/// 4) In any php file within the project you just include '411.php' and you will
///    be able to refer to any folder in your project just by typing out the folder
///    name in all caps. Like this:
///   
///    Documents/img/thumbnails/profiles/avatar.jpg
///
///    Can be shortened to:
///
///    PROFILES/avatar.jpg
///
///    And if you decide to move the profiles folder to another directory,
///    as long as you Indexify() the project nothing will break.


// Stores information about an indexed directory
class DirectoryInfo {
  public $name;
  public $pathToRoot;
  public $relativePath;
  public $nameChanged;
  public $unfixedName;
}

function Indexify() {
  // This gets every directory, subdirectory and file below this directory.
  
  $directories = array();
  $index = 0;
  
  $filesystem = 
      new RecursiveIteratorIterator (new RecursiveDirectoryIterator (__DIR__, 
        RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD
      );
  
  // Goes through each file and directory one by one...
  foreach ($filesystem as $itemPath => $itemInfo) {
    if ($itemInfo->isDir()) { // If this item is a directory.
      
      $relativePath = MakeRelativePath($itemPath);
      $pathToRoot   = GetBreadCrumbTrail($relativePath);
      $name         = GetDirectoryName($relativePath);
      
      $directories[$index] = new DirectoryInfo();

      //Fix names... no "-" are allowed in constants...
      $newname = str_replace('-', '', $name);
      $newname = str_replace('_', '', $newname);
      if ($name !== $newname) { //Name was fixed;
        $directories[$index]->unfixedName = $name;
        $directories[$index]->nameChanged = true;
        $name = $newname;
      }
          
      $directories[$index]->name          = $name;
      $directories[$index]->pathToRoot    = $pathToRoot;
      $directories[$index]->relativePath  = $relativePath;
      
      $index += 1;
    } 
    else if ($itemInfo->isFile()) { // If this item is a file.  
      switch ($itemInfo->getExtension()) {
        case 'php': // Php files...
          break;
        
        case 'html': // Html files...
          break;
        
        case 'txt':
          break;
        
        case 'jpg': 
          break;
      }
    }
  } 
  
// Create the 411 files...
  
  $file = fopen(''.'411.php','w');
  fwrite($file, '<?php'.PHP_EOL);
  fwrite($file, "define('ROOT', '');".PHP_EOL);
  foreach($directories as $directoryTo) {


      $str = "define('".strtoupper($directoryTo->name)."', '" .
                     $directoryTo->relativePath."');";

      fwrite($file, $str.PHP_EOL);
  }
  fwrite($file, '?>');
  fclose($file);
  
  foreach($directories as $directoryFrom) {
    $file = fopen($directoryFrom->relativePath.'411.php','w');
    fwrite($file, '<?php'.PHP_EOL);
    fwrite($file, "define('ROOT', '".$directoryFrom->pathToRoot."');".PHP_EOL);
    foreach($directories as $directoryTo) {
        

        $str = "define('".strtoupper($directoryTo->name)."', '" .
                       $directoryFrom->pathToRoot.$directoryTo->relativePath."');";
      
        fwrite($file, $str.PHP_EOL);
    }
    fwrite($file, '?>');
    fclose($file);
  }
  
  // DISPLAY SUMMARY
  
  echo "Indexification complete! <br><br>";
  echo count($directories)+1 . " directories indexed. <br><hr>";
  echo "Here are your directories and what they will be called:<br><br>";
  echo "<b>Please take note.</b> Directories marked with a * indicate a change from the original directory name.<br><br>";
  
  echo "<b>ROOT</b><br>";
  foreach($directories as $directory) {
    echo $directory->pathToRoot . "<b>" . strtoupper($directory->name) . "</b>/"; 
    if ($directory->nameChanged) {
      echo "* (The old name: '" . $directory->unfixedName . "' contains symbols (-, _, etc...) that can't be used!";
    }
    echo "<br>";
  }
  
}
  
function GetBreadCrumbTrail($relPath) {
  $dir = split("/", $relPath);
  for ($i = 0; $i < count($dir) - 1; $i+=1) {
    $dir[$i]="..";
  }
  $dir[count($dir) - 1] = "";
  $breadcrumb = implode('/', $dir);
  
  return $breadcrumb;
}

function GetDirectoryName($relPath) {
  $dir = split("/", $relPath);
  return $dir[count($dir)-2];
}

function MakeRelativePath($absPath) {
  $relPath = $absPath;
  $rootPath = __DIR__;
  
  $relPath = str_replace('\\', '/', $relPath);
  $rootPath = str_replace('\\', '/', $rootPath); 
  
  $relPath = str_replace($rootPath.'/', '', $relPath);
  
  return $relPath.'/';
}

?>