<!-- Block avisclient -->
<div id="avisclient_block_home" class="block">
    {if isset($avisclient_name) && $avisclient_name}
        <h4>{$avisclient_name}</h4>
    {else}
        Avis client
    {/if}
    <div class="block_content">
        <h5>{$mon_avis[0]['titre']}</h5>
        <p>{$mon_avis[0]['contenu']}</p>
        <!--
        <ul>
            <li><a href="{$avisclient_link}" title="Cliquez ici">Cliquez moi !</a></li>
        </ul>
        -->
    </div>
</div>
<!-- /Block avisclient -->