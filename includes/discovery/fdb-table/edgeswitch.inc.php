<?php

/**
 * edgeswitch.inc.php
 *
 * FDP Table discovery file for EdgeSwitch
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @link       https://www.librenms.org
 *
 * @copyright  2017 Tony Murray
 * @author     Tony Murray <murraytony@gmail.com>
 */

use Illuminate\Support\Facades\Log;
use LibreNMS\Util\Mac;

$binding = snmpwalk_group($device, 'agentDynamicDsBindingTable', 'EdgeSwitch-SWITCHING-MIB', 1);

foreach ($binding as $mac => $data) {
    $port_id = PortCache::getIdFromIfIndex($data['agentDynamicDsBindingIfIndex'], $device['device_id']);
    $mac_address = Mac::parse($mac)->hex();
    $vlan_id = $data['agentDynamicDsBindingVlanId'] ?: 0;
    $insert[$vlan_id][$mac_address]['port_id'] = $port_id;
    Log::debug("vlan $vlan_id mac $mac_address port $port_id\n");
}
