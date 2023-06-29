<x-filament::page>
	<form wire:submit.prevent="quizeQuestions" class="col-span-2 sm:col-span-1 mt-5 md:mt-0">
		<x-filament::card>
			{{ $this->updateQuizeQuestionsForm }}
			<x-slot name="footer">
        		<div class="text-right">
        			<x-filament::form.actions
		                :actions="$this->getCachedFormActions()"
		                :full-width="$this->hasFullWidthFormActions()"
		            />
        		</div>
        	</x-slot>
		</x-filament::card>
	</form>
</x-filament::page>
