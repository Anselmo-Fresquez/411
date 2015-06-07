#411 - Directory Assistance by Anselmo Fresquez III

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
