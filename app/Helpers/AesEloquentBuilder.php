<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class AesEloquentBuilder extends Builder
{
    /**
     * Determine if a column is an encrypted attribute on the model.
     */
    protected function isEncryptedColumn(string $column): bool
    {
        if (!method_exists($this->model, 'isEncryptedAttribute')) {
            return false;
        }

        // Strip table name if present (e.g. 'penduduk.nik' -> 'nik')
        $colName = str_contains($column, '.') ? explode('.', $column)[1] : $column;

        return $this->model->isEncryptedAttribute($colName);
    }

    /**
     * Add a basic where clause to the query, auto-encrypting if column is encrypted.
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_array($column)) {
            $encryptedArray = [];
            foreach ($column as $k => $v) {
                if (is_string($k) && $this->isEncryptedColumn($k)) {
                    $encryptedArray[$k] = is_string($v) ? AesSecurity::encrypt($v) : $v;
                } else {
                    $encryptedArray[$k] = $v;
                }
            }
            $column = $encryptedArray;
        } elseif (is_string($column) && $this->isEncryptedColumn($column)) {
            // Handle where('nik', $val) where 2 arguments are provided
            if (func_num_args() === 2) {
                $operator = is_string($operator) ? AesSecurity::encrypt($operator) : $operator;
            } elseif ($value !== null) {
                $value = is_string($value) ? AesSecurity::encrypt($value) : $value;
            }
        }

        return parent::where($column, $operator, $value, $boolean);
    }

    /**
     * Add a "where in" clause to the query, auto-encrypting values.
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        if (is_string($column) && $this->isEncryptedColumn($column)) {
            $encryptedValues = [];
            foreach ($values as $val) {
                $encryptedValues[] = AesSecurity::encrypt((string)$val);
            }
            $values = $encryptedValues;
        }

        return parent::whereIn($column, $values, $boolean, $not);
    }

    /**
     * Find a model by its primary key, auto-encrypting if primary key is encrypted.
     */
    public function find($id, $columns = ['*'])
    {
        if (is_string($id) && $this->isEncryptedColumn($this->model->getKeyName())) {
            $id = AesSecurity::encrypt($id);
        } elseif (is_array($id) && $this->isEncryptedColumn($this->model->getKeyName())) {
            $id = array_map(fn($v) => is_string($v) ? AesSecurity::encrypt($v) : $v, $id);
        }

        return parent::find($id, $columns);
    }
}
