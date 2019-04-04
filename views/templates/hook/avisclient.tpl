<!-- Block avisclient -->
<div id="avisclient_block_home" class="block">
    <h4>Bienvenue !</h4>
    <div class="block_content">
        <p>Bonjour,
            {if isset($avisclient_name) && $avisclient_name}
                {$avisclient_name}
            {else}
                Monde
            {/if}
            !
        </p>
        <!--
        <ul>
            <li><a href="{avisclient_link}" title="Cliquez ici">Cliquez moi !</a></li>
        </ul>
        -->
    </div>
</div>
<!-- /Block avisclient -->