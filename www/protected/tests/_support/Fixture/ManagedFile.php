<?php
namespace Fixture;

class ManagedFile
{

    public function create($data = [])
    {
        $managedFile = NULL;

        if (! empty($data['sourceFile'])) {
            $dataFilePath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_data');
            $sourceFile = $dataFilePath . DIRECTORY_SEPARATOR . $data['sourceFile'];
            $managedFile = \App\Modules\Managedfile\Models\Managedfile::createInstanceFromSourceFile($sourceFile);
        }

        //@TODO allow create from other source, e.g. url

        $managedFile->save();

        return $managedFile;
    }
}