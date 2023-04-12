<section class="w-full">
    <div class="space-y-6">
        {{-- Connections --}}
        <x-connections.last-container :title="'Last connections'" :usersList="$lastConnections"/>

        {{-- Last followers --}}
        <x-connections.last-container :title="'Last followers'" :usersList="$lastFollowers"/>

        {{-- Last following --}}
        <x-connections.last-container :title="'Last following'" :usersList="$lastFollowing"/>
    </div>
</section>
