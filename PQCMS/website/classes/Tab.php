<?php

abstract class Tab
{
    protected string $name;
    protected string $path;
    protected array $texts;

    /**
     * @param string $name
     * @param string $path
//     * @param array $texts
     */
    public function __construct(string $name, string $path, array $texts = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->texts = $texts;
        $this->texts[] = "*";
    }

    protected function generateJS($jsonTexts): string
    {
        return<<<JS
<script>
    
    {
        const texts = ${jsonTexts};
        let changes = [];
        
        for(let key in texts)
            changes[key] = false;
        
        const saveButton = createUpdateButton();
        document.querySelector("#pqcms-editor-form").appendChild(saveButton);
        
        function createUpdateButton() 
        {
            const update = document.createElement("input");
            update.type = "submit";
            update.id = "pqcms-saveButton";
            update.innerText = "Zapisz";

            update.addEventListener("click",(event) => 
            {
                event.preventDefault();
                
                let postChanges = [];
                for(let key in changes)
                    if(changes[key])
                    {
                        const inputField = document.querySelector("#pqcms-editable-textarea-"+key);
                        inputField.name = key;
                    }
                
                document.querySelector("#pqcms-editor-form").submit();
            });
            
            return update;
        }
        
        function hideUpdateButton() 
        {
            saveButton.style.opacity = "0";
            setTimeout(() => {
                saveButton.style.visilibity = "hidden";
            },1000);
        }
        
        function showUpdateButton() 
        {
            saveButton.style.visibility = "visible";
            saveButton.style.opacity = "1";
        }
        
        function isChanged() 
        {
            for(let key in changes)
                if(changes[key])
                    return true;
            return false;
        }
        
        document.querySelectorAll("textarea.pqcms-editable-textarea").forEach((e) => 
        {
            e.addEventListener("input",(event) => 
            {
                const key = event.target.id.substring(24,event.target.id.length);
                if(texts[key] === event.target.value) changes[key] = false;
                else changes[key] = true;
                    
                if(isChanged()) showUpdateButton();
                else hideUpdateButton();
            });
        });
    }
</script>
JS;
    }

    public abstract function generateHtml(bool $editable): string;
}