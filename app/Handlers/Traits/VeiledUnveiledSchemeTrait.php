<?php

namespace App\Handlers\Traits;

use App\Models\Candidate;
use App\Models\Scheme;
use App\Models\HasSet;

trait VeiledUnveiledSchemeTrait
{
    /**
     * Handle the veiled/unveiled scheme pairing logic
     */
    protected function handleVeiledUnveiledPairing(): void
    {
        // Prevent infinite loops by checking if we're already in a nested scheme call
        if ($this->isNestedSchemeCall()) {
            return; // Don't pull another scheme if we're already nested
        }

        // Get user's owned sets
        $sets = HasSet::where('data_id', $this->setup->data_id)
            ->get()
            ->toArray();
        $userSets = array_map(fn($rec) => $rec['set_value'], $sets);

        // Get the current scheme to determine if it's veiled or unveiled
        $currentScheme = $this->getCurrentScheme();

        if (!$currentScheme) {
            return;
        }

        // Determine what type of scheme to pull based on current scheme
        $targetVeiled = $currentScheme->veiled == 1 ? 0 : 1; // Opposite of current

        // Find the opposite type scheme from user's sets that isn't already in use
        $pairedScheme = Scheme::whereIn('set', $userSets)
            ->where('unveiled', $targetVeiled)
            ->whereNotIn('id', function ($query) {
                $query->select('entity_id')
                    ->from('candidates')
                    ->where('setup_id', $this->setup->id)
                    ->where('entity_type', 'schemes');
            })
            ->whereNotIn('id', function ($query) {
                $query->select('entity_id')
                    ->from('decks')
                    ->where('setup_id', $this->setup->id)
                    ->where('entity_type', 'schemes');
            })
            ->inRandomOrder()
            ->first();

        if ($pairedScheme) {
            // Create a candidate entry for the paired scheme
            $candidate = Candidate::create([
                'setup_id' => $this->setup->id,
                'entity_type' => 'schemes',
                'entity_id' => $pairedScheme->id
            ]);

            // Add the paired scheme to the deck
            $this->es->addToDeck($candidate);

            // Add expectation for the paired scheme
            $this->addExpectation($candidate);

            // Mark that we're about to run a nested scheme call
            $this->setNestedSchemeCall(true);

            // Run the handler for the paired scheme (if it exists)
            $this->runSchemeHandler($pairedScheme);

            // Clear the nested flag
            $this->setNestedSchemeCall(false);

            // Remove the candidate since it's now been processed
            $candidate->delete();
        }
    }

    /**
     * Get the current scheme being processed
     */
    private function getCurrentScheme(): ?Scheme
    {
        // Get the most recent scheme deck entry for this setup
        $latestSchemeDeck = \App\Models\Deck::where('setup_id', $this->setup->id)
            ->where('entity_type', 'schemes')
            ->latest('id')
            ->first();

        if ($latestSchemeDeck) {
            return Scheme::find($latestSchemeDeck->entity_id);
        }

        return null;
    }

    /**
     * Check if we're in a nested scheme call
     */
    private function isNestedSchemeCall(): bool
    {
        return $this->es->nestedSchemeCall === true;
    }

    /**
     * Set the nested scheme call flag
     */
    private function setNestedSchemeCall(bool $value): void
    {
        $this->es->nestedSchemeCall = $value;
    }

    /**
     * Run the appropriate handler for a scheme
     */
    private function runSchemeHandler(Scheme $scheme): void
    {
        // Get the handler class name from the scheme name
        $handlerName = $this->getHandlerClassName($scheme->name, $scheme->set);
        $handlerClass = "App\\Handlers\\Schemes\\{$handlerName}";

        // Check if the handler class exists
        if (class_exists($handlerClass)) {
            $handler = new $handlerClass($this->setup);
            $handler->execute();
        }
    }

    /**
     * Generate handler class name from scheme name and set
     */
    private function getHandlerClassName(string $schemeName, string $set): string
    {
        // Remove special characters and convert to PascalCase
        $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $schemeName);
        $cleanName = str_replace(' ', '', ucwords($cleanName));

        // Add set suffix
        $setName = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $set)));

        return $cleanName . '_' . $setName;
    }

    /**
     * Handle scheme-specific setup (twists, etc.)
     */
    protected function handleSchemeSetup(int $twists): void
    {
        $this->setup->schemes++;

        // Handle twist count logic - always use the veiled scheme's twist count
        if (!$this->isNestedSchemeCall()) {
            // Store the twist count for potential use
            $this->es->veiledSchemeTwists = $twists;

            // Get the current scheme to check if it's veiled
            $currentScheme = $this->getCurrentScheme();

            if ($currentScheme && $currentScheme->veiled == 1) {
                // This is a veiled scheme, use its twist count immediately
                $this->setup->twists = $twists;
            }
            // If unveiled scheme is first, we'll set the twist count after the veiled scheme is processed
        } else {
            // This is a nested call - if this is the veiled scheme, set its twist count
            $currentScheme = $this->getCurrentScheme();
            if ($currentScheme && $currentScheme->veiled == 1) {
                $this->setup->twists = $twists;
            }
        }
    }
}
