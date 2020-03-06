<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace GiantQuartz\SkyBlock\command\presets;


use ReflectionException;
use GiantQuartz\SkyBlock\command\IslandCommand;
use GiantQuartz\SkyBlock\session\Session;
use GiantQuartz\SkyBlock\utils\message\MessageContainer;

class MembersCommand extends IslandCommand {

    public function getName(): string {
        return "members";
    }

    public function getUsageMessageContainer(): MessageContainer {
        return new MessageContainer("MEMBERS_USAGE");
    }

    public function getDescriptionMessageContainer(): MessageContainer {
        return new MessageContainer("MEMBERS_DESCRIPTION");
    }

    /**
     * @throws ReflectionException
     */
    public function onCommand(Session $session, array $args): void {
        if($this->checkIsland($session)) {
            return;
        }
        $members = $session->getIsland()->getMembers();
        $session->sendTranslatedMessage(new MessageContainer("MEMBERS_COMMAND_HEADER", [
            "amount" => count($members)
        ]));
        foreach($members as $member) {
            $memberSession = $member->getSession();
            if($memberSession != null) {
                $session->sendTranslatedMessage(new MessageContainer("ONLINE_MEMBER", [
                    "name" => $memberSession->getName()
                ]));
            } else {
                $session->sendTranslatedMessage(new MessageContainer("OFFLINE_MEMBER", [
                    "name" => $member->getLowerCaseName()
                ]));
            }
        }
    }

}