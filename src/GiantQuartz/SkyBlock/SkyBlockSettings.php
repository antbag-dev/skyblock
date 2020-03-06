<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace GiantQuartz\SkyBlock;


use pocketmine\item\Item;
use pocketmine\utils\Config;
use GiantQuartz\SkyBlock\utils\Utils;

class SkyBlockSettings {

    private const VERSION = "1";

    /** @var SkyBlock */
    private $plugin;

    /** @var Config */
    private $settingsConfig;

    /** @var int */
    private $settingsVersion;

    /** @var int[] */
    private $slotsByCategory;

    /** @var Item[] */
    private $defaultChestContent;

    /** @var array */
    private $customChestContent;

    /** @var int */
    private $creationCooldownDuration;

    /** @var bool */
    private $cancelVoidDamage;

    /** @var array */
    private $blockedCommands = [];

    /** @var string */
    private $chatFormat;

    public function __construct(SkyBlock $plugin) {
        $this->plugin = $plugin;
        $this->refreshData();
        $this->checkVersion();
    }

    public function getSlotsByCategory(string $category): int {
        return $this->slotsByCategory[$category] ?? 1;
    }

    public function getDefaultChestContent(): array {
        return $this->defaultChestContent;
    }

    public function getCustomChestContent(string $generator): array {
        return $this->customChestContent[$generator] ?? $this->defaultChestContent;
    }

    public function getCreationCooldownDuration(): int {
        return $this->creationCooldownDuration;
    }

    public function preventVoidDamage(): bool {
        return $this->cancelVoidDamage;
    }

    /**
     * @return array
     */
    public function getBlockedCommands(): array {
        return $this->blockedCommands;
    }

    public function getChatFormat(): string {
        return $this->chatFormat;
    }

    public function refreshData(): void {
        $dataFolder = $this->plugin->getDataFolder();
        $this->settingsConfig = new Config($dataFolder . "settings.yml");
        $settingsData = $this->settingsConfig->getAll();

        $this->settingsVersion = $settingsData["Version"];
        $this->slotsByCategory = $settingsData["SlotsByCategory"];
        $this->defaultChestContent = Utils::parseItems($settingsData["ChestContent"]);

        $this->customChestContent = [];
        foreach($settingsData["CustomChestContent"] as $generator => $items) {
            if(!empty($items)) {
                $this->customChestContent[$generator] = Utils::parseItems($items);
            }
        }

        $this->creationCooldownDuration = $settingsData["CreationCooldownDuration"];
        $this->cancelVoidDamage = $settingsData["CancelVoidDamage"];
        $this->blockedCommands = $settingsData["BlockedCommands"];
        $this->chatFormat = $settingsData["ChatFormat"];
    }

    private function checkVersion(): void {
        if($this->settingsVersion == self::VERSION) {
            return;
        }
        // ToDo: Set all the new fields here
        // $this->settingsConfig->set("newField", "value");
        $this->settingsConfig->save();
        $this->plugin->getLogger()->warning("The settings version does not match with the current version of SkyBlock, all fields will have been updated");
    }

}