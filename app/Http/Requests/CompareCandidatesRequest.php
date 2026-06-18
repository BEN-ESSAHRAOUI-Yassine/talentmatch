<?php

namespace App\Http\Requests;

use App\Models\Analyse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class CompareCandidatesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('view', $this->route('offre'));
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'size:2'],
            'ids.*' => ['required', 'integer', 'exists:candidats,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $ids = $this->input('ids', []);

            if (count($ids) !== 2) {
                return;
            }

            $analyses = Analyse::query()
                ->whereIn('candidat_id', $ids)
                ->with('candidat')
                ->get();

            if ($analyses->count() !== 2) {
                $validator->errors()->add('ids', 'Un ou plusieurs candidats n\'ont pas d\'analyse.');

                return;
            }

            $offreId = $this->route('offre')->id;

            $differentOffre = $analyses->first(fn ($a) => $a->offre_id !== $offreId);

            if ($differentOffre !== null) {
                $validator->errors()->add('ids', 'Les deux candidats doivent appartenir à la même offre.');

                return;
            }

            $notCompleted = $analyses->first(fn ($a) => $a->status->value !== 'completed');

            if ($notCompleted !== null) {
                $validator->errors()->add('ids', 'Les analyses des deux candidats doivent être complétées.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Veuillez sélectionner deux candidats à comparer.',
            'ids.array' => 'Format de sélection invalide.',
            'ids.size' => 'Veuillez sélectionner exactement deux candidats.',
            'ids.*.required' => 'Identifiant de candidat requis.',
            'ids.*.integer' => 'Identifiant de candidat invalide.',
            'ids.*.exists' => 'L\'un des candidats sélectionnés n\'existe pas.',
        ];
    }
}
