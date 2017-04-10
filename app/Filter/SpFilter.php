<?php

namespace App\Filter;


class SpFilter extends QueryFilters
{
    /**
     * Filter SP data based on the SP Type
     *
     * @param $tipeSp
     * @return mixed
     */
    public function tipeSp($tipeSp)
    {
        return $this->builder->where('status_sp', $tipeSp);
    }

    /**
     * Filtering based on the SP Month
     *
     * @param $month
     * @return mixed
     */
    public function monthSp($month)
    {
        return $this->builder->whereMonth('tanggal_sp', $month);
    }

    /**
     * Filtering based on the SP Year
     *
     * @param $year
     * @return mixed
     */
    public function yearSp($year)
    {
        return $this->builder->whereYear('tanggal_sp', $year);
    }
}