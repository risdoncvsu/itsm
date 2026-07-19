loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        
        // Tell Laravel where to find the HR blade files
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'hr');
    }

    public function register()
    {
        //
    }
}